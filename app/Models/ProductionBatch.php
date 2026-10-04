<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

#[Fillable([
    'batch_number',
    'raw_product_id',
    'input_quantity',
    'start_date',
    'end_date',
    'status',
    'remarks',
    'created_by',
])]
class ProductionBatch extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'input_quantity' => 'decimal:2',
        ];
    }

    /**
     * Get the raw paddy product used as input for this batch.
     */
    public function rawProduct(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'raw_product_id');
    }

    /**
     * Get the outputs produced from this batch.
     */
    public function outputs(): HasMany
    {
        return $this->hasMany(ProductionOutput::class);
    }

    /**
     * Get the user who registered or manages this production batch.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all stock movements associated with this production batch.
     */
    public function stockMovements(): MorphMany
    {
        return $this->morphMany(StockMovement::class, 'reference');
    }
}
