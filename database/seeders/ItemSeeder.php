<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// モデル
use App\Models\Item;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Item::create([
            'item_code' => 'a-1,000',
            'item_jan_code' => '4547683000683',
            'item_name' => 'エバーカラーワンデーナチュラル ナチュラルブラウン ±0.00',
            'item_category' => 'エバーカラー',
            'model_jan_code' => '4547683000683',
            'exp_start_position' => 16,
            'lot_1_start_position' => 20,
            'lot_1_length' => 6,
            'lot_2_start_position' => 30,
            'lot_2_length' => 2,
            's_power_code' => 200,
            's_power_code_start_position' => 34,
            'is_stock_managed' => 1,
            'sort_order' => 1,
        ]);
        Item::create([
            'item_code' => 'a-1,050',
            'item_jan_code' => '4547683275333',
            'item_name' => 'エバーカラーワンデーナチュラル ナチュラルブラウン -0.50',
            'item_category' => 'エバーカラー',
            'model_jan_code' => '4547683000683',
            'exp_start_position' => 16,
            'lot_1_start_position' => 20,
            'lot_1_length' => 6,
            'lot_2_start_position' => 30,
            'lot_2_length' => 2,
            's_power_code' => 202,
            's_power_code_start_position' => 34,
            'is_stock_managed' => 1,
            'sort_order' => 2,
        ]);
        Item::create([
            'item_code' => 'a-1,075',
            'item_jan_code' => '4547683275340',
            'item_name' => 'エバーカラーワンデーナチュラル ナチュラルブラウン -0.75',
            'item_category' => 'エバーカラー',
            'model_jan_code' => '4547683000683',
            'exp_start_position' => 16,
            'lot_1_start_position' => 20,
            'lot_1_length' => 6,
            'lot_2_start_position' => 30,
            'lot_2_length' => 2,
            's_power_code' => 203,
            's_power_code_start_position' => 34,
            'is_stock_managed' => 1,
            'sort_order' => 3,
        ]);
        Item::create([
            'item_code' => 'a-1,100',
            'item_jan_code' => '4547683275357',
            'item_name' => 'エバーカラーワンデーナチュラル ナチュラルブラウン -1.00',
            'item_category' => 'エバーカラー',
            'model_jan_code' => '4547683000683',
            'exp_start_position' => 16,
            'lot_1_start_position' => 20,
            'lot_1_length' => 6,
            'lot_2_start_position' => 30,
            'lot_2_length' => 2,
            's_power_code' => 204,
            's_power_code_start_position' => 34,
            'is_stock_managed' => 1,
            'sort_order' => 4,
        ]);
        Item::create([
            'item_code' => 'a-1,125',
            'item_jan_code' => '4547683275364',
            'item_name' => 'エバーカラーワンデーナチュラル ナチュラルブラウン -1.25',
            'item_category' => 'エバーカラー',
            'model_jan_code' => '4547683000683',
            'exp_start_position' => 16,
            'lot_1_start_position' => 20,
            'lot_1_length' => 6,
            'lot_2_start_position' => 30,
            'lot_2_length' => 2,
            's_power_code' => 205,
            's_power_code_start_position' => 34,
            'is_stock_managed' => 1,
            'sort_order' => 5,
        ]);
    }
}
