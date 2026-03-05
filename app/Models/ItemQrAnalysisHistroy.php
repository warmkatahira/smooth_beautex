<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemQrAnalysisHistroy extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'item_qr_analysis_history_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'doari_qr',
        'doari_jan',
        'doari_lot',
        'doari_power',
        'item_type',
        'lot_start_position',
        's_power_code',
        's_power_code_start_position',
        'message',
    ];
    // 全てのレコードを取得
    public static function getAll()
    {
        return self::orderBy('item_qr_analysis_history_id', 'desc');
    }
}
