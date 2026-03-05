<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// モデル
use App\Models\Stock;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Stock::create(['base_id' => 'Hiroshima', 'item_id' => 1, 'total_stock' => 100, 'available_stock' => 100]);
        Stock::create(['base_id' => 'Hiroshima', 'item_id' => 2, 'total_stock' => 100, 'available_stock' => 100]);
        Stock::create(['base_id' => 'Hiroshima', 'item_id' => 3, 'total_stock' => 100, 'available_stock' => 100]);
        Stock::create(['base_id' => 'Hiroshima', 'item_id' => 4, 'total_stock' => 100, 'available_stock' => 100]);
        Stock::create(['base_id' => 'Hiroshima', 'item_id' => 5, 'total_stock' => 100, 'available_stock' => 100]);
    }
}