<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// モデル
use App\Models\EHidenVersion;

class EHidenVersionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EHidenVersion::create([
            'e_hiden_version' => 'e飛伝3',
            'file_name' => 'sagawa.xlsx',
            'file_extension' => 'xlsx',
            'data_start_row' => 2,
        ]);
    }
}
