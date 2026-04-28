<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// モデル
use App\Models\OrderCategory;

class OrderCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrderCategory::create([
            'order_category_name'   => 'INSI BEAUTY',
            'mall_id'               => 1,
            'shipper_id'            => 1,
            'label_item_name_1'     => 'コスメ',
            'sort_order'            => 1,
        ]);
        OrderCategory::create([
            'order_category_name'   => 'Push!Color',
            'mall_id'               => 2,
            'shipper_id'            => 2,
            'label_item_name_1'     => 'カラーコンタクトレンズ',
            'sort_order'            => 2,
        ]);
        OrderCategory::create([
            'order_category_name'   => 'INSI BEAUTY',
            'mall_id'               => 2,
            'shipper_id'            => 3,
            'label_item_name_1'     => 'コスメ',
            'sort_order'            => 3,
        ]);
    }
}
