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
            'order_category_name'   => 'Qoo10',
            'mall_id'               => 1,
            'shipper_id'            => 1,
            'sort_order'            => 1,
        ]);
        OrderCategory::create([
            'order_category_name'   => 'shopify_1',
            'mall_id'               => 2,
            'shipper_id'            => 1,
            'sort_order'            => 2,
        ]);
        OrderCategory::create([
            'order_category_name'   => 'shopify_2',
            'mall_id'               => 2,
            'shipper_id'            => 1,
            'sort_order'            => 3,
        ]);
    }
}
