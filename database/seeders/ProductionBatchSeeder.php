<?php

namespace Database\Seeders;

use App\Models\ProductionBatch;
use Illuminate\Database\Seeder;

class ProductionBatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductionBatch::factory()->count(8)->create();
    }
}
