<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// モデル
use App\Models\BaseShippingMethod;

class BaseShippingMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BaseShippingMethod::create([
            'shipping_method_id'    => 1,
            'base_id'               => 'Hiroshima',
            'setting_1'             => '048997010003',
            'setting_2'             => '01',
            'setting_3'             => 'A',
        ]);
        BaseShippingMethod::create([
            'shipping_method_id'    => 2,
            'base_id'               => 'Hiroshima',
            'setting_1'             => '048997010003',
            'setting_2'             => '01',
            'setting_3'             => '8',
        ]);
        BaseShippingMethod::create([
            'shipping_method_id'    => 3,
            'base_id'               => 'Hiroshima',
            'setting_1'             => '048997010003',
            'setting_2'             => '01',
            'setting_3'             => '0',
        ]);
    }
}
