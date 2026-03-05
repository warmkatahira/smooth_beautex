<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockHistoryCategory extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'stock_history_category_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'stock_history_category_name',
        'sort_order',
    ];
    // 全てのレコードを取得
    public static function getAll()
    {
        return self::orderBy('sort_order', 'asc');
    }
}
