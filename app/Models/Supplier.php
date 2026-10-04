<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'phone',
    'address',
])]
class Supplier extends Model
{
    /**
     * Get the paddy purchases from this supplier.
     */
    public function paddyPurchases(): HasMany
    {
        return $this->hasMany(PaddyPurchase::class);
    }
}
