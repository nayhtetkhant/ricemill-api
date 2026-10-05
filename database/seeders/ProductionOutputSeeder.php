<?php

namespace Database\Seeders;

use App\Models\ProductionOutput;
use Illuminate\Database\Seeder;

class ProductionOutputSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductionOutput::factory()->count(25)->create();
    }
}
