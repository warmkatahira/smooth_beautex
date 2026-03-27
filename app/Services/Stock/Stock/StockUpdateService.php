<?php

namespace App\Services\Stock\Stock;

// モデル
use App\Models\Stock;
// 列挙
use App\Enums\SystemEnum;
use App\Enums\OrderStatusEnum;
// その他
use Illuminate\Support\Str;

class StockUpdateService
{
    // 在庫を更新
    public function updateStock($request)
    {
        // 在庫を取得
        $stock = Stock::getSpecify($request->stock_id)->lockForUpdate()->first();
        // 在庫を更新
        $stock->update([
            'lot'   => $request->lot,
            'exp'   => $request->exp,
        ]);
    }
}