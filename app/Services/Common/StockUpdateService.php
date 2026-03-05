<?php

namespace App\Services\Common;

// モデル
use App\Models\Stock;
// その他
use Illuminate\Support\Facades\DB;

class StockUpdateService
{
    // 在庫数を更新
    public function updateStock($stock_update_arr)
    {
        // 在庫を変動する分だけループ処理
        foreach($stock_update_arr as $stock_update){
            // 全在庫数と有効在庫数を更新（減らす場合はマイナス符号がついているので、増やす場合と同じコードでOK）
            Stock::getSpecify($stock_update['stock_id'])
                    ->update([
                        'total_stock' => DB::raw('total_stock + ' . $stock_update['quantity']),
                        'available_stock' => DB::raw('available_stock + ' . $stock_update['quantity']),
                    ]);
        }
    }
}