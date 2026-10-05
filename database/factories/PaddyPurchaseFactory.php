<?php

namespace Database\Factories;

use App\Models\PaddyPurchase;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PaddyPurchase>
 */
class PaddyPurchaseFactory extends Factory
{
    protected $model = PaddyPurchase::class;

    public function definition(): array
    {
        $quantity = fake()->randomFloat(2, 200, 5000);
        $unitPrice = fake()->randomFloat(2, 1200, 18000);
        $totalAmount = round($quantity * $unitPrice, 2);
        $paidAmount = fake()->randomFloat(2, 0, $totalAmount);

        return [
            'purchase_number' => 'PUR-'.fake()->unique()->numerify('#######'),
            'supplier_id' => Supplier::factory(),
            'product_id' => Product::factory(),
            'purchase_date' => fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'quantity' => $quantity,
            'moisture_percentage' => fake()->randomFloat(2, 10, 25),
            'unit_price' => $unitPrice,
            'total_amount' => $totalAmount,
            'paid_amount' => $paidAmount,
            'payment_status' => fake()->randomElement(['unpaid', 'partial', 'paid']),
            'notes' => fake()->optional()->sentence(),
            'created_by' => User::factory(),
        ];
    }
}
