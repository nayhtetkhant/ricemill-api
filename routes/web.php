<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Web\InventoryController;
use App\Http\Controllers\Web\PaddyPurchaseController;
use App\Http\Controllers\Web\SaleController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('login.store');
});

Route::middleware('auth')->group(function (): void {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

    Route::resource('inventory', InventoryController::class)
        ->parameters(['inventory' => 'product'])
        ->only(['index', 'show']);

    Route::resource('purchases', PaddyPurchaseController::class)
        ->parameters(['purchases' => 'purchase'])
        ->except('update');
    Route::put('purchases/{purchase}', [PaddyPurchaseController::class, 'update'])
        ->name('purchases.update');

    Route::resource('sales', SaleController::class)->except('update');
    Route::put('sales/{sale}', [SaleController::class, 'update'])
        ->name('sales.update');
});
