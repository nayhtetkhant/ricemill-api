<?php

namespace App\Http\Controllers\Web;

use App\Enums\ProductType;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class InventoryController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['sometimes', 'string', 'max:100'],
            'type' => ['sometimes', Rule::enum(ProductType::class)],
            'stock' => ['sometimes', Rule::in(['all', 'zero', 'available'])],
        ]);

        $products = Product::query()
            ->when($filters['search'] ?? null, function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            })
            ->when($filters['type'] ?? null, fn ($query, string $type) => $query->where('type', $type))
            ->when(($filters['stock'] ?? 'all') === 'zero', fn ($query) => $query->where('current_stock', '<=', 0))
            ->when(($filters['stock'] ?? 'all') === 'available', fn ($query) => $query->where('current_stock', '>', 0))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('inventory.index', [
            'products' => $products,
            'filters' => [
                'search' => $filters['search'] ?? '',
                'type' => $filters['type'] ?? '',
                'stock' => $filters['stock'] ?? 'all',
            ],
            'productCount' => Product::query()->count(),
            'zeroStockCount' => Product::query()->where('current_stock', '<=', 0)->count(),
        ]);
    }

    public function show(Product $product): View
    {
        return view('inventory.show', [
            'product' => $product,
            'movements' => $product->stockMovements()
                ->latest('created_at')
                ->latest('id')
                ->paginate(20),
        ]);
    }
}
