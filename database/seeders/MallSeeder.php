<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// モデル
use App\Models\Mall;

class MallSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Mall::create([
            'mall_name'             => 'Qoo10',
            'mall_image_file_name'  => 'Qoo10.svg',
            'sort_order'            => 1,
        ]);
        Mall::create([
            'mall_name'             => 'shopify',
            'mall_image_file_name'  => 'shopify.svg',
            'sort_order'            => 2,
        ]);
    }
}
