<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// モデル
use App\Models\DeliveryCompany;

class DeliveryCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DeliveryCompany::create([
            'delivery_company' => 'ヤマト運輸',
            'tracking_no_url' => 'https://jizen.kuronekoyamato.co.jp/jizen/servlet/crjz.b.NQ0010?id=#tracking_no#',
            'company_image' => 'yamato.svg',
        ]);
        DeliveryCompany::create([
            'delivery_company' => '佐川急便',
            'tracking_no_url' => 'https://k2k.sagawa-exp.co.jp/p/web/okurijosearch.do?okurijoNo=#tracking_no#',
            'company_image' => 'sagawa.svg',
        ]);
    }
}
