<?php

namespace App\Services\Item\Item;

// モデル
use App\Models\Item;
use App\Models\OrderItem;
// 列挙
use App\Enums\SystemEnum;
use App\Enums\OrderStatusEnum;
// その他
use Illuminate\Support\Str;

class ItemUpdateService
{
    // 更新できる商品であるか確認
    public function checkUpdatableItem($request)
    {
        // 商品を取得
        $item = Item::getSpecify($request->item_id)->lockForUpdate()->first();
        // 在庫管理が更新されようとしている場合
        if($item->is_stock_managed != $request->is_stock_managed){
            // 出荷完了前で今回の商品が含まれている受注を取得
            $exists = OrderItem::where('order_item_code', $item->item_code)
                        ->whereHas('order', function ($query) {
                            $query->where('order_status_id', '!=', OrderStatusEnum::SHUKKA_ZUMI);
                        })
                        ->exists();
            // レコードが取得できている場合
            if($exists){
                throw new \RuntimeException('今回の商品が出荷完了前の受注に含まれているため、在庫管理を更新できません。');
            }
        }
    }

    // 商品を更新
    public function updateItem($request)
    {
        // 商品を取得
        $item = Item::getSpecify($request->item_id)->lockForUpdate()->first();
        // 商品を更新
        $item->update([
            'item_jan_code'                 => $request->item_jan_code,
            'item_name'                     => $request->item_name,
            'item_category'                 => $request->item_category,
            'model_jan_code'                => $request->model_jan_code,
            'exp_start_position'            => $request->exp_start_position,
            'lot_1_start_position'          => $request->lot_1_start_position,
            'lot_1_length'                  => $request->lot_1_length,
            'lot_2_start_position'          => $request->lot_2_start_position,
            'lot_2_length'                  => $request->lot_2_length,
            's_power_code'                  => $request->s_power_code,
            's_power_code_start_position'   => $request->s_power_code_start_position,
            'is_stock_managed'              => $request->is_stock_managed,
            'shipping_method_id'            => $request->shipping_method_id,
            'sort_order'                    => $request->sort_order,
        ]);
        return $item;
    }

    // 商品画像を削除
    public function deleteItemImage($request, $item)
    {
        // 商品画像が送られてきていない場合
        if(!$request->hasFile('image_file')){
            // 処理をスキップ
            return;
        }
        // 現在設定されている商品画像のパスを取得
        $item_image_path = storage_path('app/public/item_images/' . $item->item_image_file_name);
        // 現在設定されている商品画像が存在しているかつ、初期画像以外の場合
        if(file_exists($item_image_path) && $item->item_image_file_name != SystemEnum::DEFAULT_ITEM_IMAGE_FILE_NAME){
            // 商品画像を削除
            unlink($item_image_path);
        }
        return;
    }

    // 商品画像を保存
    public function saveItemImage($request, $item)
    {
        // 商品画像が送られてきていない場合
        if(!$request->hasFile('image_file')){
            // 処理をスキップ
            return;
        }
        // 商品画像を取得
        $image = $request->file('image_file');
        // 商品画像の拡張子を取得（例: jpg, png）
        $extension = $image->getClientOriginalExtension();
        // 保存するファイル名を設定（uuid + 拡張子）
        $item_image_file_name = (string) Str::uuid() . '.' . $extension;
        // 保存するパスを設定
        $item_image_path = storage_path('app/public/item_images');
        // 商品画像を保存
        $image->move($item_image_path, $item_image_file_name);
        // 商品画像ファイル名を更新
        $item->update([
            'item_image_file_name' => $item_image_file_name,
        ]);
        return;
    }
}