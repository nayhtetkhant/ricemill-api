<?php

namespace App\Models;

use App\Enums\ProductType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'code',
    'type',
    'unit',
    'current_stock',
    'unit_price',
])]
class Product extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => ProductType::class,
            'current_stock' => 'decimal:2',
            'unit_price' => 'decimal:2',
        ];
    }

    /**
     * Get the purchases for this raw paddy product.
     */
    public function paddyPurchases(): HasMany
    {
        return $this->hasMany(PaddyPurchase::class);
    }

    /**
     * Get the production batches using this product as input raw material.
     */
    public function productionBatches(): HasMany
    {
        return $this->hasMany(ProductionBatch::class, 'raw_product_id');
    }

    /**
     * Get the production outputs generated as this product.
     */
    public function productionOutputs(): HasMany
    {
        return $this->hasMany(ProductionOutput::class);
    }

    /**
     * Get the stock movements for this product.
     */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Get the sale items containing this product.
     */
    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }
}
