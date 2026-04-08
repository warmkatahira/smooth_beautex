<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// モデル
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'user_id' => 'katahira',
            'last_name' => 'システム管理者',
            'first_name' => '',
            'email' => 't.katahira@warm.co.jp',
            'password' => bcrypt('katahira134'),
            'status' => 1,
            'role_id' => 'admin',
            'company_id' => 'warm',
            'is_must_change_password' => false,
        ]);
        User::create([
            'user_id' => 'oizumi046',
            'last_name' => '大泉',
            'first_name' => '一弘',
            'email' => 'ooizumi@warm.co.jp',
            'password' => bcrypt('kjq12zbv'),
            'status' => 1,
            'role_id' => 'user',
            'company_id' => 'warm',
            'is_must_change_password' => true,
        ]);
        User::create([
            'user_id' => 'shim@eleanor.co.jp',
            'last_name' => '清水',
            'first_name' => '厚芳',
            'email' => 'shim@eleanor.co.jp',
            'password' => bcrypt('adw66tj'),
            'status' => 1,
            'role_id' => 'user',
            'company_id' => 'beautex',
            'is_must_change_password' => true,
        ]);
        User::create([
            'user_id' => 'iwase@beautex.co.jp',
            'last_name' => '岩瀬',
            'first_name' => '直紀',
            'email' => 'iwase@beautex.co.jp',
            'password' => bcrypt('m0diu4fd'),
            'status' => 1,
            'role_id' => 'user',
            'company_id' => 'beautex',
            'is_must_change_password' => true,
        ]);
        User::create([
            'user_id' => 'hirano@eleanor.co.jp',
            'last_name' => '平野',
            'first_name' => '愛理',
            'email' => 'hirano@eleanor.co.jp',
            'password' => bcrypt('jp43qdc'),
            'status' => 1,
            'role_id' => 'user',
            'company_id' => 'beautex',
            'is_must_change_password' => true,
        ]);
    }
}