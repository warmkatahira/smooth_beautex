<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// その他
use Carbon\CarbonImmutable;

class ShippingWorkEndHistory extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'shipping_work_end_history_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'target_count',
        'is_successful',
        'message',
    ];
    // 10日前までのデータを取得
    public static function getDispData()
    {
        // 10日前の日付を取得
        $tenDaysAgo = CarbonImmutable::now()->subDays(10);
        return self::where('created_at', '>=', $tenDaysAgo)->orderBy('created_at', 'desc');
    }
    // 「is_successful」に基づいて、有効 or 無効を返すアクセサ
    public function getStatusTextAttribute(): string
    {
        return $this->is_successful ? '成功' : '失敗';
    }
}
