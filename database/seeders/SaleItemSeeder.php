<?php

namespace Database\Seeders;

use App\Models\SaleItem;
use Illuminate\Database\Seeder;

class SaleItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SaleItem::factory()->count(30)->create();
    }
}
