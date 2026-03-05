<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// モデル
use App\Models\Base;

class BaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Base::create([
            'base_id' => '1st',
            'base_name' => '第1営業所',
            'base_color_code' => '#e5fff4',
            'mieru_customer_code' => 'beautex_1st',
            'sort_order' => 1,
        ]);
    }
}
