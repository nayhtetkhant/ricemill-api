<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Sale>
 */
class SaleFactory extends Factory
{
    protected $model = Sale::class;

    public function definition(): array
    {
        $totalAmount = fake()->randomFloat(2, 500, 250000);
        $paidAmount = fake()->randomFloat(2, 0, $totalAmount);

        return [
            'invoice_number' => 'INV-'.fake()->unique()->numerify('#######'),
            'customer_id' => Customer::factory(),
            'sale_date' => fake()->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
            'total_amount' => $totalAmount,
            'paid_amount' => $paidAmount,
            'payment_status' => fake()->randomElement(['unpaid', 'partial', 'paid']),
            'notes' => fake()->optional()->sentence(),
            'created_by' => User::factory(),
        ];
    }
}
