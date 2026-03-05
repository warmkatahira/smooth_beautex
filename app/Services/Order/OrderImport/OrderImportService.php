<?php

namespace App\Services\Order\OrderImport;

// モデル
use App\Models\OrderImport;
use App\Models\OrderImportHistory;
use App\Models\Prefecture;
use App\Models\Order;
use App\Models\OrderItem;
// サービス
use App\Services\Common\ImportErrorCreateService;
// 列挙
use App\Enums\OrderStatusEnum;
use App\Enums\SystemEnum;
use App\Enums\OrderCategoryEnum;
use App\Enums\ShippingMethodEnum;
// 例外
use App\Exceptions\OrderImportException;
// その他
use Carbon\CarbonImmutable;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class OrderImportService
{
    // 選択したデータをストレージにインポート
    public function importData($select_file)
    {
        // 現在の日時を取得
        $nowDate = CarbonImmutable::now();
        // 選択したデータのファイル名を取得
        $original_file_name = $select_file->getClientOriginalName();
        // ストレージに保存する際のファイル名を設定
        $save_file_name = 'order_import_data_'.$nowDate->format('Y-m-d H-i-s').'.xlsx';
        // ファイルを保存して保存先のパスを取得
        $save_file_path = Storage::disk('public')->putFileAs('import/order_import', $select_file, $save_file_name);
        // 保存先のパスがNullの場合
        if(is_null($save_file_path)){
            throw new OrderImportException('受注データが認識できませんでした。', $import_info, null, null, null);
        }
        // フルパスに調整する
        return with([
            'original_file_name' => $original_file_name,
            'save_file_path' => Storage::disk('public')->path($save_file_path),
        ]);
    }

    // インポートした受注区分を取得
    public function getOrderCategoryId($save_file_path)
    {
        // 全データを取得
        $all_line = (new FastExcel)->import($save_file_path);
        // データが空の場合
        if($all_line->isEmpty()){
            throw new OrderImportException('データがありませんでした。', null, null, null);
        }
        // インポートしたデータのヘッダーを取得
        $import_data_header = array_keys((array) $all_line[0]);
        // Qoo10のidを返す
        return OrderCategoryEnum::QOO10_ID;

    }

    // インポートしたデータのヘッダーを確認
    public function checkHeader($save_file_path, $nowDate, $import_info, $order_category_id)
    {
        // 全データを取得
        $all_line = (new FastExcel)->import($save_file_path);
        // インポートしたデータのヘッダーを取得
        $import_data_header = array_keys((array) $all_line[0]);
        // システムに定義している必須ヘッダーを取得
        $require_header = OrderImport::requireHeaderForOrderImport($order_category_id);
        // ヘッダーの分だけループ処理
        foreach($require_header as $header){
            // ヘッダーが存在するか確認
            $result = $this->checkValueExists($import_data_header, $header);
            // nullではない場合
            if(!is_null($result)){
                // エラーファイルを作成
                $error_file_name = $ImportErrorCreateService->createImportError('受注取込エラー', array($result), $nowDate, 'ヘッダー不正', null);
                throw new OrderImportException('ファイルが正しくない為、取り込みできませんでした。', $import_info, null, $error_file_name);
            }
        }
    }

    // 配列の値が存在しているか確認
    public function checkValueExists($array, $value){
        // 存在したら「true」、存在しなかったら「false」
        $result = in_array($value, $array);
        // 存在しなかったら、エラーを返す
        return !$result ? 'カラムに「'.$value.'」がありません。' : null;
    }

    // order_importsへデータを追加
    public function createArrayImportData($order)
    {
        // テーブルをロック
        OrderImport::select()->lockForUpdate()->get();
        // 追加先のテーブルをクリア
        OrderImport::query()->delete();
        // 200件ごとにデータを分ける
        $chunks = array_chunk($order, 200);
        // 分割した分だけループ処理
        foreach($chunks as $chunk){
            // テーブルに追加
            OrderImport::insert($chunk);
        }
    }

    // 既に取込済みの受注を削除して、削除後の注文番号数を返す
    public function deleteImportedOrder()
    {
        // インポート済みの情報を格納する配列を初期化
        $import_already = [];
        // 削除処理前の注文番号数を取得
        $before_order_no_num = OrderImport::groupBy('order_no')->select('order_no')->get()->count();
        // ordersテーブルとorder_importsテーブルを注文番号と受注区分と荷送人で結合
        $orders = Order::join('order_imports', function($join){
                            $join->on('order_imports.order_no', 'orders.order_no')
                            ->on('order_imports.order_category_id', 'orders.order_category_id')
                            ->on('order_imports.shipper_id', 'orders.shipper_id');
                        })
                        ->groupBy('order_imports.order_no')
                        ->select('order_imports.order_no')
                        ->get();
        // 削除対象がいれば、情報をセッションに格納
        if($orders->count() > 0){
            $import_already = $orders->toArray();
        }
        // 既に取り込まれている受注をorder_importsテーブルから削除
        OrderImport::whereIn('order_no', $orders)->delete();
        // 削除処理後の注文番号数を取得
        $after_order_no_num = OrderImport::groupBy('order_no')->select('order_no')->get()->count();
        // 削除された注文番号数を取得(削除前 - 削除後)
        $delete_order_no_num = $before_order_no_num - $after_order_no_num;
        return compact('before_order_no_num', 'after_order_no_num', 'delete_order_no_num', 'import_already');
    }

    // 出荷倉庫IDを更新
    public function updateShippingBaseId()
    {
        // 都道府県名でテーブルを結合して、prefecturesの出荷倉庫IDでorder_importsの出荷倉庫IDを更新する
        DB::statement("
            UPDATE order_imports
            JOIN prefectures ON prefectures.prefecture_name = order_imports.ship_prefecture_name
            SET order_imports.shipping_base_id = prefectures.shipping_base_id
        ");
    }

    // 受注管理IDを採番
    public function updateOrderControlId()
    {
        // 受注管理IDの先頭10桁(先頭固定WEを含む)に使用する文字列をランダムで生成し、既に使用されていないか確認する
        // 採番が終わったかを判定する変数を初期化
        $check = false;
        // $checkがtrueになるまでループ処理
        while(!$check){
            // 文字列を生成
            $order_control_id_head = 'MO'.Str::random(9);
            // LIKE検索で生成した文字列をordersテーブルでカウント
            $count = Order::where('order_control_id', 'LIKE', '%'.$order_control_id_head.'%')->count();
            // countが0の場合
            if($count === 0){
                // 存在していないので、trueをセット（番号が決まったので処理を抜ける）
                $check = true;
            }
        }
        // 重複を取り除いた注文番号を取得
        $orders = OrderImport::select('order_no')->distinct()->get();
        // 受注管理IDの連番で使用する変数をセット
        $count = 0;
        // 注文番号の分だけループ処理
        foreach($orders as $order){
            // 受注管理IDを採番
            $count++;
            $order_control_id = $order_control_id_head . sprintf('%05d', $count);
            // 受注管理IDを更新
            OrderImport::where('order_no', $order->order_no)->update([
                'order_control_id' => $order_control_id,
            ]);
        }
    }

    // ordersとorder_itemsテーブルへ追加
    public function createOrder()
    {
        // ordersテーブルに追加する情報を取得
        $create_order = OrderImport::createTargetListForOrder(OrderImport::query())->get();
        // 重複を取り除きコレクションを配列に変換して取得
        $create_order_unique = collect($create_order)->unique()->toArray();
        // ordersテーブルに追加
        Order::upsert($create_order_unique, 'order_id');
        // order_itemsテーブルに追加する情報を取得
        $create_order_item = OrderImport::createTargetListForOrderItem(OrderImport::query())->get()->toArray();
        // order_itemsテーブルに追加
        OrderItem::upsert($create_order_item, 'order_item_id');
    }

    // order_import_historiesテーブルへ追加
    public function createOrderImportHistory($import_info, $order_no_num, $error_file_name, $message)
    {
        // 追加
        OrderImportHistory::create([
            'import_file_name'  => isset($import_info['original_file_name']) ? $import_info['original_file_name'] : null,
            'all_order_num'     => is_null($order_no_num) ? null : $order_no_num['before_order_no_num'],
            'import_order_num'  => is_null($order_no_num) ? null : $order_no_num['after_order_no_num'],
            'delete_order_num'  => is_null($order_no_num) ? null : $order_no_num['delete_order_no_num'],
            'error_file_name'   => $error_file_name,
            'message'           => $message,
        ]);
    }

    // 表示するメッセージを作成
    public function createDispMessage($result)
    {
        // 固定のメッセージをセット
        $message = $result['before_order_no_num'].'件中'.$result['after_order_no_num'].'件の受注データを取り込みしました。';
        // 削除された件数があれば、メッセージを追加
        if($result['delete_order_no_num'] > 0){
            $message .= '<br>取込不可：'.$result['delete_order_no_num'].'件';
        }
        return with([
            'type' => $result['delete_order_no_num'] > 0 ? 'warning' : 'success',
            'message' => $message,
        ]);
    }
}