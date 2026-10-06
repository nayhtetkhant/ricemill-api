<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SaleService
{
    public function __construct(private InventoryService $inventory) {}

    /** @param array<string, mixed> $data */
    public function create(array $data, ?int $userId): Sale
    {
        return $this->inventory->transaction(function () use ($data, $userId): Sale {
            $items = $data['items'];
            $products = $this->inventory->lockProducts(array_column($items, 'product_id'));
            $total = $this->total($items);
            $paid = round((float) ($data['paid_amount'] ?? 0), 2);
            $this->assertPaymentDoesNotExceedTotal($paid, $total);

            $sale = Sale::query()->create([
                ...Arr::only($data, ['customer_id', 'sale_date', 'notes']),
                'invoice_number' => 'INV-'.now()->format('YmdHis').'-'.Str::upper(Str::random(6)),
                'total_amount' => $total,
                'paid_amount' => $paid,
                'payment_status' => $this->paymentStatus($paid, $total),
                'created_by' => $userId,
            ]);

            $this->replaceItems($sale, $items, $products, $userId, false);

            return $sale->load(['customer', 'items.product']);
        });
    }

    /** @param array<string, mixed> $data */
    public function update(Sale $sale, array $data, ?int $userId): Sale
    {
        return $this->inventory->transaction(function () use ($sale, $data, $userId): Sale {
            $sale = Sale::query()->lockForUpdate()->findOrFail($sale->id);
            $oldItems = $sale->items()->lockForUpdate()->get();
            $items = $data['items'];
            $products = $this->inventory->lockProducts([
                ...$oldItems->pluck('product_id')->map(fn ($id): int => (int) $id)->all(),
                ...array_map(fn (array $item): int => (int) $item['product_id'], $items),
            ]);

            foreach ($oldItems as $oldItem) {
                $this->inventory->change(
                    $products->get($oldItem->product_id),
                    (float) $oldItem->quantity,
                    'adjustment',
                    $sale,
                    $userId,
                    "Sale stock reversed for update: {$sale->invoice_number}",
                );
            }

            $total = $this->total($items);
            $paid = round((float) ($data['paid_amount'] ?? 0), 2);
            $this->assertPaymentDoesNotExceedTotal($paid, $total);

            $sale->update([
                ...Arr::only($data, ['customer_id', 'sale_date', 'notes']),
                'total_amount' => $total,
                'paid_amount' => $paid,
                'payment_status' => $this->paymentStatus($paid, $total),
            ]);

            $this->replaceItems($sale, $items, $products, $userId, true);

            return $sale->load(['customer', 'items.product']);
        });
    }

    public function delete(Sale $sale, ?int $userId): void
    {
        $this->inventory->transaction(function () use ($sale, $userId): void {
            $sale = Sale::query()->lockForUpdate()->findOrFail($sale->id);
            $items = $sale->items()->lockForUpdate()->get();
            $products = $this->inventory->lockProducts($items->pluck('product_id')->map(fn ($id): int => (int) $id)->all());

            foreach ($items as $item) {
                $this->inventory->change(
                    $products->get($item->product_id),
                    (float) $item->quantity,
                    'adjustment',
                    $sale,
                    $userId,
                    "Sale voided: {$sale->invoice_number}",
                );

                $item->delete();
            }

            $sale->delete();
        });
    }

    /**
     * @param  array<int, array{product_id: int|string, quantity: int|float|string, unit_price: int|float|string}>  $items
     * @param  Collection<int, Product>  $products
     */
    private function replaceItems(Sale $sale, array $items, $products, ?int $userId, bool $deleteExisting): void
    {
        if ($deleteExisting) {
            $sale->items()->get()->each(fn (SaleItem $item) => $item->delete());
        }

        foreach ($items as $item) {
            $product = $products->get((int) $item['product_id']);
            $quantity = round((float) $item['quantity'], 2);
            $unitPrice = round((float) $item['unit_price'], 2);
            $subtotal = round($quantity * $unitPrice, 2);

            $saleItem = $sale->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'subtotal' => $subtotal,
            ]);

            $this->inventory->change(
                $product,
                -$quantity,
                'sale_out',
                $sale,
                $userId,
                "Sale item {$saleItem->id}: {$sale->invoice_number}",
            );
        }
    }

    /** @param array<int, array{quantity: int|float|string, unit_price: int|float|string}> $items */
    private function total(array $items): float
    {
        return round(array_sum(array_map(
            fn (array $item): float => round((float) $item['quantity'] * (float) $item['unit_price'], 2),
            $items,
        )), 2);
    }

    private function paymentStatus(float $paid, float $total): string
    {
        return match (true) {
            $paid <= 0 => 'unpaid',
            $paid >= $total => 'paid',
            default => 'partial',
        };
    }

    private function assertPaymentDoesNotExceedTotal(float $paid, float $total): void
    {
        if ($paid > $total) {
            throw ValidationException::withMessages([
                'paid_amount' => 'The paid amount may not exceed the transaction total.',
            ]);
        }
    }
}
