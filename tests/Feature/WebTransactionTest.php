<?php

use App\Enums\ProductType;
use App\Models\Customer;
use App\Models\PaddyPurchase;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest is redirected to sign in before reaching transaction pages', function () {
    $this->get('/')->assertRedirect(route('login'));
    $this->get('/purchases/create')->assertRedirect(route('login'));
    $this->get('/sales/create')->assertRedirect(route('login'));
});

test('user can sign in and sign out', function () {
    $user = User::factory()->create(['email' => 'mill@example.com']);

    $this->get('/login')
        ->assertOk()
        ->assertSee('Sign in');

    $this->post('/login', [
        'email' => 'mill@example.com',
        'password' => 'password',
    ])->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
    $this->get('/')->assertOk()->assertSee('Paddy purchases');

    $this->post('/logout')->assertRedirect(route('login'));
    $this->assertGuest();
});

test('purchase forms use the purchase transaction service', function () {
    $user = User::factory()->create();
    $supplier = Supplier::factory()->create();
    $product = Product::factory()->create([
        'type' => ProductType::RawMaterial,
        'current_stock' => 40,
    ]);
    $this->actingAs($user);

    $this->get('/purchases/create')
        ->assertOk()
        ->assertSee('Record paddy purchase');

    $this->post('/purchases', [
        'supplier_id' => $supplier->id,
        'product_id' => $product->id,
        'purchase_date' => '2026-10-05',
        'quantity' => 8,
        'unit_price' => 15,
        'paid_amount' => 30,
    ])->assertRedirect();

    $purchase = PaddyPurchase::query()->firstOrFail();
    expect((float) $product->fresh()->current_stock)->toBe(48.0);

    $this->get(route('purchases.show', $purchase))
        ->assertOk()
        ->assertSee($purchase->purchase_number)
        ->assertSee('Void purchase');

    $this->put(route('purchases.update', $purchase), [
        'supplier_id' => $supplier->id,
        'product_id' => $product->id,
        'purchase_date' => '2026-10-05',
        'quantity' => 5,
        'unit_price' => 15,
        'paid_amount' => 20,
    ])->assertRedirect(route('purchases.show', $purchase));

    expect((float) $product->fresh()->current_stock)->toBe(45.0);

    $this->delete(route('purchases.destroy', $purchase))
        ->assertRedirect(route('purchases.index'));

    expect((float) $product->fresh()->current_stock)->toBe(40.0);
    $this->assertSoftDeleted('paddy_purchases', ['id' => $purchase->id]);
});

test('purchase form rejects paid amount above total without changing stock', function () {
    $user = User::factory()->create();
    $supplier = Supplier::factory()->create();
    $product = Product::factory()->create([
        'type' => ProductType::RawMaterial,
        'current_stock' => 0,
    ]);

    $this->actingAs($user)->from('/purchases/create')->post('/purchases', [
        'supplier_id' => $supplier->id,
        'product_id' => $product->id,
        'purchase_date' => '2026-10-05',
        'quantity' => 2,
        'unit_price' => 10,
        'paid_amount' => 21,
    ])->assertRedirect('/purchases/create')
        ->assertSessionHasErrors('paid_amount');

    expect(PaddyPurchase::query()->count())->toBe(0);
    expect((float) $product->fresh()->current_stock)->toBe(0.0);
});

test('sale forms use the sale transaction service', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create();
    $product = Product::factory()->create([
        'type' => ProductType::Finished,
        'current_stock' => 12,
    ]);
    $this->actingAs($user);

    $this->get('/sales/create')
        ->assertOk()
        ->assertSee('Invoice items')
        ->assertSee('Add line');

    $this->post('/sales', [
        'customer_id' => $customer->id,
        'sale_date' => '2026-10-05',
        'paid_amount' => 0,
        'items' => [
            ['product_id' => $product->id, 'quantity' => 3, 'unit_price' => 25],
        ],
    ])->assertRedirect();

    $sale = Sale::query()->firstOrFail();
    expect((float) $sale->total_amount)->toBe(75.0);
    expect((float) $product->fresh()->current_stock)->toBe(9.0);

    $this->get(route('sales.show', $sale))
        ->assertOk()
        ->assertSee($sale->invoice_number)
        ->assertSee('Void sale');

    $this->put(route('sales.update', $sale), [
        'customer_id' => $customer->id,
        'sale_date' => '2026-10-05',
        'paid_amount' => 10,
        'items' => [
            ['product_id' => $product->id, 'quantity' => 2, 'unit_price' => 25],
        ],
    ])->assertRedirect(route('sales.show', $sale));

    expect((float) $product->fresh()->current_stock)->toBe(10.0);

    $this->delete(route('sales.destroy', $sale))
        ->assertRedirect(route('sales.index'));

    expect((float) $product->fresh()->current_stock)->toBe(12.0);
    $this->assertSoftDeleted('sales', ['id' => $sale->id]);
});

test('sale replacement rolls back when updated lines exceed available stock', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create();
    $product = Product::factory()->create([
        'type' => ProductType::Finished,
        'current_stock' => 5,
    ]);
    $this->actingAs($user);

    $this->post('/sales', [
        'customer_id' => $customer->id,
        'sale_date' => '2026-10-05',
        'paid_amount' => 0,
        'items' => [
            ['product_id' => $product->id, 'quantity' => 2, 'unit_price' => 10],
        ],
    ])->assertSessionHasNoErrors();

    $sale = Sale::query()->firstOrFail();

    $this->from(route('sales.edit', $sale))->put(route('sales.update', $sale), [
        'customer_id' => $customer->id,
        'sale_date' => '2026-10-05',
        'paid_amount' => 0,
        'items' => [
            ['product_id' => $product->id, 'quantity' => 10, 'unit_price' => 10],
        ],
    ])->assertRedirect(route('sales.edit', $sale))
        ->assertSessionHasErrors('stock');

    expect((float) $product->fresh()->current_stock)->toBe(3.0);
    expect((float) $sale->fresh()->total_amount)->toBe(20.0);
    expect($sale->items()->count())->toBe(1);
});
