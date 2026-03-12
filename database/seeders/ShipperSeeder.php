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
            'shipper_company_name'  => 'BEAUTEX株式会社',
            'shipper_name'          => 'INSI BEAUTY（Qoo10）',
            'shipper_zip_code'      => '340-0815',
            'shipper_address'       => '埼玉県八潮市八潮5-5-2',
            'shipper_tel'           => '03-6899-3994',
        ]);
        Shipper::create([
            'shipper_company_name'  => 'BEAUTEX株式会社',
            'shipper_name'          => 'Push!Color',
            'shipper_zip_code'      => '340-0815',
            'shipper_address'       => '埼玉県八潮市八潮5-5-2',
            'shipper_tel'           => '03-6899-3994',
        ]);
        Shipper::create([
            'shipper_company_name'  => 'BEAUTEX株式会社',
            'shipper_name'          => 'INSI BEAUTY',
            'shipper_zip_code'      => '340-0815',
            'shipper_address'       => '埼玉県八潮市八潮5-5-2',
            'shipper_tel'           => '03-6899-3994',
        ]);
    }
}
