<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            BaseSeeder::class,
            CompanySeeder::class,
            UserSeeder::class,
            PrefectureSeeder::class,
            DeliveryCompanySeeder::class,
            ShippingMethodSeeder::class,
            EHidenVersionSeeder::class,
            BaseShippingMethodSeeder::class,
            ShipperSeeder::class,
            //ItemSeeder::class,
            //StockSeeder::class,
            StockHistoryCategorySeeder::class,
            OrderCategorySeeder::class,
            //AutoProcessSeeder::class,
        ]);
    }
}
