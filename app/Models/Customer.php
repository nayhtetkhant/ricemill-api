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
class Customer extends Model
{
    /**
     * Get the sales for this customer.
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
}
