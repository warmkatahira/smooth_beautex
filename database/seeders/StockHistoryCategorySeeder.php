<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// モデル
use App\Models\StockHistoryCategory;

class StockHistoryCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StockHistoryCategory::create([
            'stock_history_category_name' => '入庫',
            'sort_order' => 1,
        ]);
        StockHistoryCategory::create([
            'stock_history_category_name' => '出荷',
            'sort_order' => 2,
        ]);
        StockHistoryCategory::create([
            'stock_history_category_name' => '調整',
            'sort_order' => 3,
        ]);
    }
}
