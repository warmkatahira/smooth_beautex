<?php

namespace App\Services\Setting\OrderCategory;

// モデル
use App\Models\OrderCategory;
// 列挙
use App\Enums\SystemEnum;
// その他
use Illuminate\Support\Str;

class OrderCategoryUpdateService
{
    // 受注区分を更新
    public function updateOrderCategory($request)
    {
        // 受注区分を取得
        $order_category = OrderCategory::getSpecify($request->order_category_id)->first();
        // 受注区分を更新
        $order_category->update([
            'order_category_name' => $request->order_category_name,
            'shipper_id' => $request->shipper_id,
            'sort_order' => $request->sort_order,
        ]);
        return $order_category;
    }

    // 受注区分画像を削除
    public function deleteOrderCategoryImage($request, $order_category)
    {
        // 商品画像が送られてきていない場合
        if(!$request->hasFile('image_file')){
            // 処理をスキップ
            return;
        }
        // 現在設定されている受注区分画像のパスを取得
        $order_category_image_path = storage_path('app/public/order_category_images/' . $order_category->order_category_image_file_name);
        // 現在設定されている受注区分画像が存在しているかつ、初期画像以外の場合
        if(file_exists($order_category_image_path) && $order_category->order_category_image_file_name != SystemEnum::DEFAULT_ORDER_CATEGORY_IMAGE_FILE_NAME){
            // 受注区分画像を削除
            unlink($order_category_image_path);
        }
    }

    // 受注区分画像を保存
    public function saveOrderCategoryImage($request, $order_category)
    {
        // 受注区分画像が送られてきていない場合
        if(!$request->hasFile('image_file')){
            // 処理をスキップ
            return;
        }
        // 受注区分画像を取得
        $image = $request->file('image_file');
        // 受注区分画像の拡張子を取得（例: jpg, png）
        $extension = $image->getClientOriginalExtension();
        // 保存するファイル名を設定（uuid + 拡張子）
        $order_category_image_file_name = (string) Str::uuid() . '.' . $extension;
        // 保存するパスを設定
        $order_category_image_path = storage_path('app/public/order_category_images');
        // 受注区分画像を保存
        $image->move($order_category_image_path, $order_category_image_file_name);
        // 受注区分画像ファイル名を更新
        $order_category->update([
            'order_category_image_file_name' => $order_category_image_file_name,
        ]);
    }
}