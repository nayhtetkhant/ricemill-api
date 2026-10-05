<?php

use App\Enums\ProductType;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductionBatch;
use App\Models\Sale;
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

    $this->get('/')
        ->assertOk()
        ->assertSee('Operations overview')
        ->assertSee('125,000')
        ->assertSee('INV-20261001')
        ->assertSee('Mya Thida')
        ->assertSee('640.00')
        ->assertSee('BATCH-20261001')
        ->assertDontSee('BATCH-20261002')
        ->assertSee('1 open');
});
