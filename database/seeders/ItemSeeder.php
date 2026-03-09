<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// モデル
use App\Models\Item;
// その他
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/sql/item.sql');
        // SQLファイルの読み込み
        $sql = File::get($path);
        // SQL実行
        DB::unprepared($sql);
    }
}
