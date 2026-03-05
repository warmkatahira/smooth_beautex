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
            'order_category_image_file_name'  => 'Qoo10.svg',
            'shipper_id'            => 1,
            'sort_order'            => 1,
        ]);
    }
}
