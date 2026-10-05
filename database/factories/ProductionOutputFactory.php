<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductionBatch;
use App\Models\ProductionOutput;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductionOutput>
 */
class ProductionOutputFactory extends Factory
{
    protected $model = ProductionOutput::class;

    public function definition(): array
    {
        return [
            'production_batch_id' => ProductionBatch::factory(),
            'product_id' => Product::factory(),
            'quantity' => fake()->randomFloat(2, 50, 4000),
        ];
    }
}
