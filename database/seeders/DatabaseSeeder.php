<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        foreach ([
            'stock_movements',
            'sale_items',
            'sales',
            'production_outputs',
            'production_batches',
            'paddy_purchases',
            'products',
            'customers',
            'suppliers',
            'users',
        ] as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
            }
        }

        Schema::enableForeignKeyConstraints();

        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'role' => UserRole::Staff,
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
            ]
        );

        $this->call([
            SupplierSeeder::class,
            CustomerSeeder::class,
            ProductSeeder::class,
            PaddyPurchaseSeeder::class,
            ProductionBatchSeeder::class,
            ProductionOutputSeeder::class,
            SaleSeeder::class,
            SaleItemSeeder::class,
            StockMovementSeeder::class,
        ]);
    }
}
