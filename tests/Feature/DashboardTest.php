<?php

use App\Enums\ProductType;
use App\Models\Customer;
use App\Models\PaddyPurchase;
use App\Models\Product;
use App\Models\ProductionBatch;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('dashboard displays current rice mill data', function () {
    $product = Product::factory()->create([
        'name' => 'Premium Rice',
        'type' => ProductType::Finished,
        'current_stock' => 640,
    ]);
    $customer = Customer::factory()->create(['name' => 'Mya Thida']);
    Sale::factory()->for($customer)->create([
        'invoice_number' => 'INV-20261001',
        'sale_date' => now()->toDateString(),
        'total_amount' => 125000,
        'paid_amount' => 25000,
        'payment_status' => 'partial',
    ]);
    $rawPaddy = Product::factory()->create([
        'name' => 'Harvest Paddy',
        'type' => ProductType::RawMaterial,
        'current_stock' => 250,
    ]);
    PaddyPurchase::factory()->for(Supplier::factory())->for($rawPaddy)->create([
        'purchase_number' => 'PUR-20261001',
        'purchase_date' => now()->toDateString(),
        'quantity' => 250,
        'unit_price' => 10,
        'total_amount' => 2500,
    ]);
    ProductionBatch::factory()->create([
        'raw_product_id' => $product->id,
        'batch_number' => 'BATCH-20261001',
        'start_date' => now()->toDateString(),
        'end_date' => null,
        'status' => 'processing',
        'input_quantity' => 420,
    ]);
    ProductionBatch::factory()->create([
        'raw_product_id' => $product->id,
        'batch_number' => 'BATCH-20261002',
        'start_date' => now()->toDateString(),
        'end_date' => null,
        'status' => 'cancelled',
    ]);

    $this->actingAs(User::factory()->create())->get('/')
        ->assertOk()
        ->assertSee('Operations overview')
        ->assertSee('125,000')
        ->assertSee('INV-20261001')
        ->assertSee('Mya Thida')
        ->assertSee('Recent paddy purchases')
        ->assertSee('PUR-20261001')
        ->assertSee('640.00')
        ->assertSee('BATCH-20261001')
        ->assertDontSee('BATCH-20261002')
        ->assertSee('1 open');
});
