<?php

use App\Enums\ProductType;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('inventory can be searched and filtered by stock state and product type', function () {
    $user = User::factory()->create();
    $availableRaw = Product::factory()->create([
        'name' => 'Paddy Golden',
        'code' => 'RAW-GOLD',
        'type' => ProductType::RawMaterial,
        'current_stock' => 20,
    ]);
    Product::factory()->create([
        'name' => 'White Rice',
        'code' => 'RICE-WHITE',
        'type' => ProductType::Finished,
        'current_stock' => 0,
    ]);

    $this->actingAs($user)->get('/inventory')
        ->assertOk()
        ->assertSee('Paddy Golden')
        ->assertSee('White Rice');

    $this->get('/inventory?search=RAW-GOLD&type=RAW_MATERIAL&stock=available')
        ->assertOk()
        ->assertSee('Paddy Golden')
        ->assertDontSee('White Rice');

    $this->get('/inventory?stock=zero')
        ->assertOk()
        ->assertDontSee('Paddy Golden')
        ->assertSee('White Rice');

    $this->get(route('inventory.show', $availableRaw))
        ->assertOk()
        ->assertSee('Stock movement history')
        ->assertSee('Record paddy purchase');
});

test('inventory product detail displays stock movement history', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create([
        'name' => 'Brown Paddy',
        'type' => ProductType::RawMaterial,
        'current_stock' => 35,
    ]);
    StockMovement::factory()->create([
        'product_id' => $product->id,
        'type' => 'purchase_in',
        'quantity' => 35,
        'balance_after' => 35,
        'reference_type' => null,
        'reference_id' => null,
        'notes' => 'Opening receipt',
    ]);

    $this->actingAs($user)->get(route('inventory.show', $product))
        ->assertOk()
        ->assertSee('Brown Paddy')
        ->assertSee('Purchase In')
        ->assertSee('Opening receipt')
        ->assertSee('35.00');
});
