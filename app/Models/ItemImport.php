<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemImport extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'item_import_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'item_code',
        'item_jan_code',
        'item_name',
        'item_category',
        'model_jan_code',
        'exp_start_position',
        'lot_1_start_position',
        'lot_1_length',
        'lot_2_start_position',
        'lot_2_length',
        's_power_code',
        's_power_code_start_position',
        'is_stock_managed',
        'sort_order',
    ];
    // itemsテーブルとのリレーション
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_code', 'item_code');
    }
}
