<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InventoryService
{
    /**
     * @param  array<int, int>  $productIds
     * @return Collection<int, Product>
     */
    public function lockProducts(array $productIds): Collection
    {
        $productIds = array_values(array_unique(array_map('intval', $productIds)));

        $products = Product::query()
            ->whereIn('id', $productIds)
            ->orderBy('id')
            ->lockForUpdate()
            ->get()
            ->keyBy('id');

        if ($products->count() !== count($productIds)) {
            throw (new ModelNotFoundException)->setModel(Product::class, $productIds);
        }

        return $products;
    }

    public function change(
        Product $product,
        float $quantityChange,
        string $type,
        Model $reference,
        ?int $userId,
        string $notes,
    ): StockMovement {
        $newBalance = round((float) $product->current_stock + $quantityChange, 2);

        if ($newBalance < 0) {
            throw ValidationException::withMessages([
                'stock' => "Insufficient stock for {$product->name}.",
            ]);
        }

        $product->forceFill(['current_stock' => $newBalance])->save();

        return $reference->stockMovements()->create([
            'product_id' => $product->id,
            'type' => $type,
            'quantity' => round($quantityChange, 2),
            'balance_after' => $newBalance,
            'created_by' => $userId,
            'notes' => $notes,
        ]);
    }

    public function transaction(callable $callback): mixed
    {
        return DB::transaction($callback, attempts: 3);
    }
}
