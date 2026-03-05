<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// その他
use Carbon\CarbonImmutable;

class OrderImportHistory extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'order_import_history_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'import_file_name',
        'all_order_num',
        'import_order_num',
        'delete_order_num',
        'error_file_name',
        'message',
    ];
    // 10日前までのデータを取得
    public static function getDispData()
    {
        // 10日前の日付を取得
        $tenDaysAgo = CarbonImmutable::now()->subDays(10);
        return self::where('created_at', '>=', $tenDaysAgo)->orderBy('created_at', 'desc');
    }
}
