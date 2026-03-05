<?php

namespace App\Services\Order\OrderMgt;

// モデル
use App\Models\Order;
// 列挙
use App\Enums\SystemEnum;
use App\Enums\OrderStatusEnum;
use App\Enums\RouteNameEnum;
// その他
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class OrderSearchService
{
    // セッションを削除
    public function deleteSession()
    {
        session()->forget([
            'search_shipping_date_from',
            'search_shipping_date_to',
            'search_order_no',
            'search_order_control_id',
            'search_order_mark',
            'search_order_category_id',
            'search_shipping_base_id',
            'search_ship_name',
            'search_ship_prefecture_name',
            'search_shipping_method_id',
            'search_desired_delivery_date',
            'search_is_shipping_inspection_complete',
        ]);
        // 出荷管理以外のページの場合
        if(Route::currentRouteName() !== RouteNameEnum::SHIPPING_MGT){
            session()->forget('search_shipping_group_id');
        }
    }

    // セッションに検索条件を格納
    public function setSearchCondition($request)
    {
        // 現在のURLを取得
        session(['back_url_1' => url()->full()]);
        // パラメータがあればパラメータを活かし、なければ指定した値をセット
        session(['search_order_status_id' => isset($request->order_status_id) ? $request->order_status_id : OrderStatusEnum::KAKUNIN_MACHI ]);
        // 変数が存在しない場合は検索が実行されていないので、初期条件をセット
        if(!isset($request->search_type)){
        }
        // 「search」なら検索が実行されているので、検索条件をセット
        if($request->search_type === 'search'){
            session(['search_order_no' => $request->search_order_no]);
            session(['search_order_control_id' => $request->search_order_control_id]);
            session(['search_order_mark' => $request->search_order_mark]);
            session(['search_order_category_id' => $request->search_order_category_id]);
            session(['search_shipping_base_id' => $request->search_shipping_base_id]);
            session(['search_ship_name' => $request->search_ship_name]);
            session(['search_ship_prefecture_name' => $request->search_ship_prefecture_name]);
            session(['search_shipping_method_id' => $request->search_shipping_method_id]);
            session(['search_desired_delivery_date' => $request->search_desired_delivery_date]);
            session(['search_is_shipping_inspection_complete' => $request->search_is_shipping_inspection_complete]);
        }
        return;
    }

    // 検索結果を取得
    public function getSearchResult()
    {
        // 注文ステータスを指定して受注データを取得
        $query = Order::where('order_status_id', session('search_order_status_id'))
                    ->with('order_items.item');
        // 出荷日の条件がある場合
        if (!empty(session('search_shipping_date_from')) && !empty(session('search_shipping_date_to'))) {
            $query = $query->whereDate('shipping_date', '>=', session('search_shipping_date_from'))
                            ->whereDate('shipping_date', '<=', session('search_shipping_date_to'));
        }
        // 出荷グループの条件がある場合（出荷管理の時のみ）
        if (session('search_shipping_group_id') != null) {
            $query = $query->where('shipping_group_id', session('search_shipping_group_id'));
        }
        // 注文番号の条件がある場合
        if(session('search_order_no') != null){
            // 条件を指定して取得
            $query = $query->where('order_no', 'LIKE', '%'.session('search_order_no').'%');
        }
        // 受注管理IDの条件がある場合
        if(session('search_order_control_id') != null){
            // 条件を指定して取得
            $query = $query->where('order_control_id', 'LIKE', '%'.session('search_order_control_id').'%');
        }
        // 受注マークの条件がある場合
        if(session('search_order_mark') != null){
            // 条件を指定して取得
            $query = $query->where('order_mark', 'LIKE', '%'.session('search_order_mark').'%');
        }
        // 受注区分の条件がある場合
        if(session('search_order_category_id') != null){
            // 条件を指定して取得
            $query = $query->where('order_category_id', session('search_order_category_id'));
        }
        // 出荷倉庫の条件がある場合
        if(session('search_shipping_base_id') != null){
            // 条件を指定して取得
            $query = $query->where('shipping_base_id', session('search_shipping_base_id'));
        }
        // 配送先名の条件がある場合
        if(session('search_ship_name') != null){
            // 条件を指定して取得
            $query = $query->where('ship_name', 'LIKE', '%'.session('search_ship_name').'%');
        }
        // 配送先都道府県の条件がある場合
        if(session('search_ship_prefecture_name') != null){
            // 条件を指定して取得
            $query = $query->where('ship_prefecture_name', session('search_ship_prefecture_name'));
        }
        // 配送方法の条件がある場合
        if(session('search_shipping_method_id') != null){
            // 条件を指定して取得
            $query = $query->where('shipping_method_id', session('search_shipping_method_id'));
        }
        // 配送希望日の条件がある場合
        if(session('search_desired_delivery_date') != null){
            // 条件を指定して取得(指定した日付よりも小さい日付を取得　※Nullも取得している)
            $query = $query->where(function ($q) {
                        $q->whereDate('desired_delivery_date', '<=', session('search_desired_delivery_date'))
                            ->orWhereNull('desired_delivery_date');
                    });
        }
        // 出荷検品状態の条件がある場合
        if(session('search_is_shipping_inspection_complete') != null){
            // 条件を指定して取得
            $query = $query->where('is_shipping_inspection_complete', session('search_is_shipping_inspection_complete'));
        }
        // 並び替えを実施
        return $query->orderBy('order_import_date', 'asc')
                    ->orderBy('order_import_time', 'asc')
                    ->orderBy('order_control_id', 'asc');
    }

    // ページネーションを実施
    public function setPagination($query)
    {
        // 指定された件数でページネーション
        return $query->paginate(SystemEnum::PAGINATE_DEFAULT);
    }
}