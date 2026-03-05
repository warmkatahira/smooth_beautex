<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// モデル
use App\Models\AutoProcess;
use App\Models\AutoProcessCondition;

class AutoProcessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AutoProcess::create([
            'auto_process_name'     => '☆マーク付与',
            'action_type'           => 'order_mark_update',
            'action_column_name'    => 'order_mark',
            'action_value'          => '☆',
            'is_active'             => 1,
        ]);
        AutoProcessCondition::create([
            'auto_process_id'   => 1,
            'column_name'       => 'order_item_code',
            'operator'          => '=',
            'value'             => 'a-1,050',
        ]);
    }
}
