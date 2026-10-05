<?php

namespace Database\Factories;

use App\Enums\ProductType;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'code' => strtoupper(fake()->unique()->bothify('PRD-####')),
            'type' => fake()->randomElement(ProductType::cases()),
            'unit' => fake()->randomElement(['kg', 'bag', 'sack', 'ton']),
            'current_stock' => fake()->randomFloat(2, 0, 5000),
            'unit_price' => fake()->randomFloat(2, 50, 5000),
        ];
    }
}
