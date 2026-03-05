<?php

namespace App\Services\Order\OrderImport;

// モデル
use App\Models\OrderImport;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\AutoProcess;
use App\Models\AutoProcessOrderItem;
use App\Models\AutoProcessCondition;
use App\Models\Item;
use App\Models\Stock;
// 列挙
use App\Enums\AutoProcessEnum;

class AutoProcessApplyService
{
    // 自動処理を適用
    public function apply()
    {
        // is_activeが「1」(有効)の自動処理を取得
        $auto_processes = AutoProcess::getIsActive()->with('auto_process_order_item')->with('auto_process_conditions')->lockForUpdate()->get();
        // 子テーブルをロック
        $auto_process_ids = $auto_processes->pluck('auto_process_id');
        $auto_process_order_items = AutoProcessOrderItem::whereIn('auto_process_id', $auto_process_ids)->lockForUpdate()->get();
        $auto_process_conditions = AutoProcessCondition::whereIn('auto_process_id', $auto_process_ids)->lockForUpdate()->get();
        // 適用できる自動処理がない場合
        if($auto_processes->isEmpty()){
            // 処理を終了
            return;
        }
        // 自動処理を適用する受注管理IDを取得(order_importsとordersを結合することにより、今回取り込んだ受注のみが対象となるようにしている)
        $order_control_ids = OrderImport::join('orders', 'orders.order_control_id', 'order_imports.order_control_id')
                                ->pluck('orders.order_control_id');
        // 対象の受注を取得
        $orders = Order::whereIn('order_control_id', $order_control_ids)->with('order_items')->lockForUpdate()->get();
        // 自動処理の分だけループ処理
        foreach($auto_processes as $auto_process){
            // 受注の分だけループ処理
            foreach($orders as $order){
                // 自動処理を適用するか判定する変数を初期化
                $matched = $auto_process->condition_match_type === AutoProcessEnum::ALL ? true : false;
                // 自動処理条件の分だけループ処理
                foreach($auto_process->auto_process_conditions as $auto_process_condition){
                    // カラム名からテーブル名を取得
                    $table = AutoProcessEnum::TABLE_MAPPING[$auto_process_condition->column_name] ?? null;
                    // 条件に一致したかを判定する変数を初期化
                    $condition_result = false;
                    // テーブル名が「orders」の場合
                    if($table === AutoProcessEnum::ORDERS){
                        // 自動処理条件に一致するか確認
                        $condition_result = AutoProcessEnum::checkCondition($order, $auto_process_condition);
                    // テーブル名が「order_items」の場合
                    }elseif($table === AutoProcessEnum::ORDER_ITEMS){
                        // 条件に一致したかを判定する変数を初期化
                        $item_matched = false;
                        // order_itemsの分だけループ処理
                        foreach($order->order_items as $order_item){
                            // 自動処理条件に一致する場合
                            if(AutoProcessEnum::checkCondition($order_item, $auto_process_condition)){
                                // trueに更新して処理を抜ける
                                $condition_result = true;
                                break;
                            }
                        }
                    }
                    // 全てを満たす場合
                    if($auto_process->condition_match_type === AutoProcessEnum::ALL){
                        // falseの場合
                        if(!$condition_result){
                            $matched = false;
                            break;
                        }
                    // いずれかを満たす場合
                    }elseif($auto_process->condition_match_type === AutoProcessEnum::ANY){
                        // trueの場合
                        if($condition_result){
                            $matched = true;
                            break;
                        }
                    }
                }
                // trueの場合(自動処理を適用する場合)
                if($matched){
                    // 受注商品を追加の場合
                    if($auto_process->action_type === AutoProcessEnum::ORDER_ITEM_CREATE){
                        // 受注商品追加処理
                        $this->createOrderItem($order, $auto_process);
                    // それ以外の場合
                    }else{
                        // 更新処理
                        $order->{$auto_process->action_column_name} = $auto_process->action_value;
                        $order->save();
                    }
                }
            }
        }
    }

    // 受注商品追加処理
    public function createOrderItem($order, $auto_process)
    {
        // すでに同じ商品が存在していないか確認
        $exists = OrderItem::getSpecifyByOrderControlId($order->order_control_id)
                    ->where('order_item_code', $auto_process->auto_process_order_item->order_item_code)
                    ->exists();
        // 存在していない場合
        if(!$exists){
            // 追加する商品を取得
            $item = Item::getSpecifyByItemCode($auto_process->auto_process_order_item->order_item_code)->lockForUpdate()->first();
            // 商品が存在しない場合
            if(empty($item)){
                // 処理を抜ける
                return;
            }
            // 在庫管理を行っている場合は、在庫のチェックを行う
            if($item->is_stock_managed){
                // 追加する商品の在庫を取得
                $stock = Stock::getSpecifyByBaseIdItemId($order->shipping_base_id, $item->item_id)->lockForUpdate()->first();
                // 在庫レコードが存在しないまたは、有効在庫数が出荷数よりも小さい場合
                if(empty($stock) || $stock->available_stock < $auto_process->auto_process_order_item->order_quantity){
                    // 処理を抜ける
                    return;
                }
            }
            // レコードを追加
            OrderItem::create([
                'order_control_id'      => $order->order_control_id,
                'is_item_allocated'     => 1,
                'is_stock_allocated'    => 1,
                'unallocated_quantity'  => 0,
                'order_item_code'       => $auto_process->auto_process_order_item->order_item_code,
                'order_item_name'       => $auto_process->auto_process_order_item->order_item_name,
                'order_quantity'        => $auto_process->auto_process_order_item->order_quantity,
                'is_auto_process_add'   => 1,
            ]);
            // 在庫管理を行っている場合
            if($item->is_stock_managed){
                // 有効在庫数から出荷数を引く
                $stock->decrement('available_stock', $auto_process->auto_process_order_item->order_quantity);
            }
        }
    }
}