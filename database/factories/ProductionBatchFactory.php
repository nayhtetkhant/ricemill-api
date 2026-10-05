<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductionBatch;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductionBatch>
 */
class ProductionBatchFactory extends Factory
{
    protected $model = ProductionBatch::class;

    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-90 days', '-10 days');
        $endDate = fake()->optional()->dateTimeBetween($startDate, 'now');

        return [
            'batch_number' => 'BATCH-'.fake()->unique()->numerify('#######'),
            'raw_product_id' => Product::factory(),
            'input_quantity' => fake()->randomFloat(2, 500, 15000),
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate ? $endDate->format('Y-m-d') : null,
            'status' => fake()->randomElement(['pending', 'processing', 'completed', 'cancelled']),
            'remarks' => fake()->optional()->sentence(),
            'created_by' => User::factory(),
        ];
    }
}
