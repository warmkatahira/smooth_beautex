<?php

namespace App\Services\Item\Item;

// モデル
use App\Models\Item;
use App\Models\Stock;

class ItemDeleteService
{
    // 商品が削除可能か確認
    public function checkDeletable($request)
    {
        // 商品と在庫を取得
        $item = Item::getSpecify($request->item_id)->withCount('order_items')->lockForUpdate()->first();
        $stocks = Stock::getSpecifyByItemId($request->item_id)->lockForUpdate()->get();
        // 受注に存在する商品の場合
        if($item->order_items_count > 0){
            throw new \RuntimeException('使用されている商品のため、削除できません。');
        }
        // 在庫数が1以上の場合
        if($stocks->where('total_stock', '>=', 1)->isNotEmpty()){
            throw new \RuntimeException('在庫数が1以上あるため、削除できません。');
        }
        return $item;
    }

    // 商品を削除
    public function deleteItem($item)
    {
        // 在庫を削除
        Stock::getSpecifyByItemId($item->item_id)->delete();
        // 商品を削除
        $item->delete();
    }
}