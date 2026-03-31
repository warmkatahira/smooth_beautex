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
        'item_category_1',
        'item_category_2',
        'is_inspection_lot_required',
        'model_jan_code',
        'exp_start_position',
        'lot_1_start_position',
        'lot_1_length',
        'lot_2_start_position',
        'lot_2_length',
        's_power_code',
        's_power_code_start_position',
        'is_stock_managed',
        'country_of_origin',
        'hs_code',
        'item_weight_g',
        'brand',
        'wearing_period',
        'quantity_per_box',
        'color_id',
        'color_row',
        'manufacturer',
        'supplier',
        'sort_order',
    ];
    // itemsテーブルとのリレーション
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_code', 'item_code');
    }
}
