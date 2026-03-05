<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockHistoryDetail extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'stock_history_detail_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'stock_history_id',
        'stock_id',
        'quantity',
    ];
}
