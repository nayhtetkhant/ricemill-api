<?php

namespace Database\Factories;

use App\Models\PaddyPurchase;
use App\Models\Product;
use App\Models\ProductionBatch;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StockMovement>
 */
class StockMovementFactory extends Factory
{
    protected $model = StockMovement::class;

    public function definition(): array
    {
        $references = [
            PaddyPurchase::class,
            ProductionBatch::class,
            Sale::class,
            SaleItem::class,
        ];

        $referenceType = fake()->randomElement($references);
        $referenceId = $referenceType::query()->inRandomOrder()->value('id');

        if ($referenceId === null) {
            $referenceId = $referenceType::factory()->create()->id;
        }

        return [
            'product_id' => Product::factory(),
            'type' => fake()->randomElement(['purchase_in', 'production_input', 'production_output', 'sale_out', 'adjustment']),
            'quantity' => fake()->randomFloat(2, 1, 500),
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'balance_after' => fake()->randomFloat(2, 0, 10000),
            'notes' => fake()->sentence(),
            'created_by' => User::factory(),
        ];
    }
}
