<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// モデル
use App\Models\Company;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::create([
            'company_id' => 'warm',
            'company_name' => '株式会社ワーム',
            'sort_order' => 1,
        ]);
        Company::create([
            'company_id' => 'momochi',
            'company_name' => '株式会社百道',
            'sort_order' => 2,
        ]);
    }
}
