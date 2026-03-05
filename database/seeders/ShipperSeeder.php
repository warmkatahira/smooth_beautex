<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// モデル
use App\Models\Shipper;

class ShipperSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Shipper::create([
            'shipper_company_name' => '株式会社 百道',
            'shipper_name' => 'レンズショップmomo/モモ',
            'shipper_zip_code' => '810-0001',
            'shipper_address' => '福岡県福岡市中央区天神2丁目3-10 天神パインクレスト716',
            'shipper_tel' => '092-577-0150',
        ]);
    }
}
