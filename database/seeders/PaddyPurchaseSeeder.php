<?php

namespace Database\Seeders;

use App\Models\PaddyPurchase;
use Illuminate\Database\Seeder;

class PaddyPurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaddyPurchase::factory()->count(20)->create();
    }
}
