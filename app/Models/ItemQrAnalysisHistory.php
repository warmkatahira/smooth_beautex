<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemQrAnalysisHistory extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'item_qr_analysis_history_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'qr_code',
        'jan_code',
        'lot',
        'is_jan_code_match',
        'power',
        's_power_code',
        's_power_code_start_position',
        'is_lot_match',
        'lot_start_position',
        'lot_length',
        'exp_start_position',
        'exp',
        'user_no',
    ];
    // 全てのレコードを取得
    public static function getAll()
    {
        return self::orderBy('created_at', 'desc')->limit(50);
    }
    // 指定したレコードを取得
    public static function getSpecify($item_qr_analysis_history_id)
    {
        return self::where('item_qr_analysis_history_id', $item_qr_analysis_history_id);
    }
    // usersテーブルとのリレーション
    public function user()
    {
        return $this->belongsTo(User::class, 'user_no', 'user_no');
    }
}
