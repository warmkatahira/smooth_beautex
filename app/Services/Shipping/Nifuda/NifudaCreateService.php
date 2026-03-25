<?php

namespace App\Services\Shipping\Nifuda;

// モデル
use App\Models\Order;
use App\Models\ShippingGroup;
use App\Models\ShippingMethod;
use App\Models\BaseShippingMethod;
use App\Models\YamatoSorting;
use App\Models\NifudaCreateHistory;
// 列挙
use App\Enums\OrderStatusEnum;
use App\Enums\DeliveryCompanyEnum;
use App\Enums\DeliveryTimeZoneEnum;
use App\Enums\SystemEnum;
use App\Enums\ShippingMethodEnum;
use App\Enums\SagawaSealCodeEnum;
// その他
use Carbon\CarbonImmutable;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NifudaCreateService
{
    // 作成対象を取得
    public function getCreateOrder($shipping_method_id)
    {
        // 指定された出荷グループ×配送方法の受注を取得
        $orders = Order::with(['order_items.item', 'order_category.shipper'])
                    ->where('shipping_group_id', session('filter_shipping_group_id'))
                    ->where('shipping_method_id', $shipping_method_id)
                    ->orderBy('order_control_id');
        // 作成できる荷札データがない場合
        if(!$orders->exists()){
            throw new \RuntimeException('作成できる荷札データがありません。');
        }
        return $orders;
    }

    // 荷札データを作成
    public function createNifuda($shipping_method_id, $orders)
    {
        // 現在の日時を取得
        $nowDate = CarbonImmutable::now();
        // 出荷グループを取得
        $shipping_group = ShippingGroup::getSpecify(session('filter_shipping_group_id'))->first();
        // 配送方法を取得
        $shipping_method = ShippingMethod::getSpecify($shipping_method_id)->first();
        // 倉庫別配送方法を取得
        $base_shipping_method = BaseShippingMethod::getSpecifyByBaseIdAndShippingMethodId($shipping_group->shipping_base_id, $shipping_method_id)->first();
        // 保存先のディレクトリ名を決める
        $directory_name = $shipping_group->shipping_group_name.'_'.$shipping_method->delivery_company_and_shipping_method.'_'.$nowDate->format('Y-m-d_H-i-s');
        // 既に存在しているディレクトリではない場合
        if(!Storage::disk('public')->exists('nifuda/'.$directory_name)){
            // 保存先のディレクトリを作成
            Storage::disk('public')->makeDirectory('nifuda/'.$directory_name);
        }
        // 運送会社によって処理を可変
        // 佐川急便
        if($shipping_method->delivery_company_id === DeliveryCompanyEnum::SAGAWA){
            // 国内は「xlsx」、海外は「csv」
            $file_extension = ShippingMethodEnum::SAGAWA_EMS_ID === $shipping_method->shipping_method_id ? 'csv' : 'xlsx';
            // ファイル名を取得
            $download_filename = '【'.$shipping_method->delivery_company_and_shipping_method.'】荷札データ_'.$nowDate->isoFormat('Y年MM月DD日HH時mm分ss秒').'.'.$file_extension;
            // 配送方法が佐川EMSの場合
            if(ShippingMethodEnum::SAGAWA_EMS_ID === $shipping_method->shipping_method_id){
                // 海外用
                $this->createSagawaForGlobal($orders, $shipping_group, $download_filename, $directory_name);
            }else{
                // 国内用
                $this->createSagawaForJp($base_shipping_method, $orders, $shipping_group, $download_filename, $directory_name);
            }
        }
        // ヤマト運輸
        if($shipping_method->delivery_company_id === DeliveryCompanyEnum::YAMATO){
            $download_filename = '【'.$shipping_method->delivery_company_and_shipping_method.'】荷札データ_'.$nowDate->isoFormat('Y年MM月DD日HH時mm分ss秒').'.xlsx';
            $this->createYamato($base_shipping_method, $orders, $shipping_group, $download_filename, $directory_name);
        }
        return $directory_name;
    }

    // 佐川急便(海外用)
    public function createSagawaForGlobal($orders, $shipping_group, $download_filename, $directory_name)
    {
        // 作成ファイル数をカウントする変数を初期化
        $make_file_count = 0;
        // チャンクサイズを指定
        $chunk_size = 1000;
        // レコードをチャンクごとに書き込む
        $orders->chunk($chunk_size, function ($orders) use ($shipping_group, $download_filename, $directory_name, &$make_file_count) {
            // 作成ファイル数をカウントアップ
            $make_file_count++;
            // テンプレートを読み込む
            $templatePath = public_path('template/sagawa_global.csv');
            $spreadsheet = IOFactory::load($templatePath);
            $worksheet = $spreadsheet->getActiveSheet();
            // データを書き込む位置を初期化
            $row = 2;
            // 受注の分だけループ処理
            foreach($orders as $order){
                // 内容品のオフセット用の変数を初期化
                $column_offset = 0;
                // 出荷人会社名を変数に格納
                // ship_country_codeが「US」の場合は「NAOKI IWASE」、それ以外は「BEAUTEX Corp. / Push!Color」
                $shipper_company_name = $order->ship_country_code == 'US' ? 'NAOKI IWASE' : 'BEAUTEX Corp. / Push!Color';
                // ship_country_codeが「US」の場合は「1」(ギフト)、それ以外は「3」(販売品)
                $content_type = $order->ship_country_code == 'US' ? 0 : 3;
                // 各情報を出力
                $worksheet->setCellValue('A'.$row, $shipper_company_name);                                                  // 出荷人会社名
                $worksheet->setCellValue('B'.$row, $order->ship_name);                                                      // 受取人お名前
                $worksheet->setCellValue('C'.$row, "");                                                                     // 受取人会社名
                $worksheet->setCellValue('E'.$row, $order->ship_country_code);                                              // 受取人国名
                $worksheet->setCellValue('G'.$row, $order->ship_address_1);                                                 // 受取人住所2
                $worksheet->setCellValue('H'.$row, $order->ship_address_2.','.$order->ship_city);                           // 受取人住所3
                $worksheet->setCellValue('I'.$row, $order->ship_province_code);                                             // 受取人州名など
                $worksheet->setCellValue('J'.$row, $order->ship_zip_code);                                                  // 受取人郵便番号
                $worksheet->setCellValue('K'.$row, $order->ship_tel);                                                       // 受取人ご連絡先電話番号
                $worksheet->setCellValue('N'.$row, $content_type);                                                          // 内容品種別
                $worksheet->setCellValue('P'.$row, $order->order_items->sum(
                                                fn($item) => ($item->item->item_weight_g ?? 0) * $item->shipping_quantity
                                            ));                                                                             // 総重量
                $worksheet->setCellValue('W'.$row, $order->order_control_id);                                               // メモ
                $worksheet->setCellValue('X'.$row, $order->subtotal);                                                       // 総商品金額(JPY)
                // order_itemsの分だけループ処理
                foreach($order->order_items as $order_item){
                    // 基準列（Y = 25列目）からのオフセットを加味して列を計算
                    $colY  = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(25 + $column_offset);
                    $colZ  = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(26 + $column_offset);
                    $colAA = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(27 + $column_offset);
                    $colAB = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(28 + $column_offset);
                    $colAC = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(29 + $column_offset);
                    $colAE = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(31 + $column_offset);
                    // 各情報を出力
                    $worksheet->setCellValue($colY . $row, 'ColoredContactLens');                   // 内容品名
                    $worksheet->setCellValue($colZ . $row, $order_item->item->hs_code);             // HSコード
                    $worksheet->setCellValue($colAA . $row, $order_item->shipping_quantity);        // 個数
                    $worksheet->setCellValue($colAB . $row, $order_item->order_item_unit_price);    // 単価
                    $worksheet->setCellValue($colAC . $row, 'JPY');                                 // 通貨単位
                    $worksheet->setCellValue($colAE . $row, $order_item->item->country_of_origin);  // 単価
                    // オフセットに+7する（右に7列分ずらすため）
                    $column_offset += 7;
                }
                // データを書き込む位置をカウントアップ
                $row++;
            }
            // ファイルの保存先パスを取得
            $file_path = Storage::disk('public')->path('nifuda/'.$directory_name.'/【'.sprintf('%02d', $make_file_count).'】'.$download_filename);
            // CSVファイルを保存する
            $writer = IOFactory::createWriter($spreadsheet, 'Csv');
            $writer->setUseBOM(true);
            $writer->save($file_path);
        });
        return;
    }

    // 佐川急便(国内用)
    public function createSagawaForJp($base_shipping_method, $orders, $shipping_group, $download_filename, $directory_name)
    {
        // 作成ファイル数をカウントする変数を初期化
        $make_file_count = 0;
        // チャンクサイズを指定
        $chunk_size = 1000;
        // レコードをチャンクごとに書き込む
        $orders->chunk($chunk_size, function ($orders) use ($base_shipping_method, $shipping_group, $download_filename, $directory_name, &$make_file_count) {
            // 作成ファイル数をカウントアップ
            $make_file_count++;
            // テンプレートを読み込む
            $templatePath = public_path('template/sagawa.xlsx');
            $spreadsheet = IOFactory::load($templatePath);
            $worksheet = $spreadsheet->getActiveSheet();
            // データを書き込む位置を初期化
            $row = $base_shipping_method->e_hiden_version->data_start_row;
            // 受注の分だけループ処理
            foreach($orders as $order){
                // 配送先住所と荷送人住所からスペースを取り除く
                $ship_address = str_replace(array(" ", "　"), "", $order->ship_address);
                $shipper_address = str_replace(array(" ", "　"), "", $order->order_category->shipper->shipper_address);
                // 各情報を出力
                $worksheet->setCellValue('C'.$row, $order->ship_tel);   // 配送先電話番号
                $worksheet->setCellValue('D'.$row, $order->ship_zip_code);  // 配送先郵便番号
                $worksheet->setCellValue('E'.$row, $ship_address);    // 配送先住所
                $worksheet->setCellValue('H'.$row, $order->ship_name.$order->ship_staff_name);  // 配送先名
                $worksheet->setCellValue('J'.$row, $order->order_control_id);   // 受注管理ID
                $worksheet->setCellValue('K'.$row, $base_shipping_method->setting_1); // お客様コード
                $worksheet->setCellValue('Q'.$row, $base_shipping_method->setting_1); // ご依頼主コード
                $worksheet->setCellValue('R'.$row, $order->order_category->shipper->shipper_tel); // ご依頼主電話番号
                $worksheet->setCellValue('S'.$row, $order->order_category->shipper->shipper_zip_code); // ご依頼主郵便番号
                $worksheet->setCellValue('T'.$row, $shipper_address); // ご依頼主住所
                $worksheet->setCellValue('V'.$row, $order->order_category->shipper->shipper_name); // ご依頼主名
                $worksheet->setCellValue('Y'.$row, $order->order_no); // 品名1
                $worksheet->setCellValue('Z'.$row, 'コンタクトレンズ'); // 品名2
                $worksheet->setCellValue('AS'.$row, is_null($order->desired_delivery_date) ? '' : CarbonImmutable::parse($order->desired_delivery_date)->format('Y/m/d')); // 配送希望日
                $worksheet->setCellValue('AT'.$row, DeliveryTimeZoneEnum::sagawa_time_zone_get($order->desired_delivery_time));   // 配送希望時間
                $worksheet->setCellValue('AZ'.$row, '011'); // 指定シール1(取注)
                $worksheet->setCellValue('BA'.$row, SagawaSealCodeEnum::sagawa_seal_code_get($base_shipping_method->e_hiden_version, $order->desired_delivery_date, $order->desired_delivery_time)); // 指定シール2(日時指定)
                $worksheet->setCellValue('BB'.$row, ''); // 指定シール3
                $worksheet->setCellValue('BI'.$row, CarbonImmutable::parse($shipping_group->estimated_shipping_date)->format('Y/m/d'));  // 出荷日
                // データを書き込む位置をカウントアップ
                $row++;
            }
            // 拡張子がxlsxの場合
            if($base_shipping_method->e_hiden_version->file_extension === 'xlsx'){
                // ファイルの保存先パスを取得
                $file_path = Storage::disk('public')->path('nifuda/'.$directory_name.'/【'.sprintf('%02d', $make_file_count).'】'.$download_filename);
                // Excelファイルを保存する
                $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                $writer->save($file_path);
            }
            // 拡張子がcsvの場合
            if($base_shipping_method->e_hiden_version->file_extension === 'csv'){
                // 一時的にUTF-8で保存
                $utf8_path = Storage::disk('local')->path('temp.csv');
                $writer = IOFactory::createWriter($spreadsheet, 'Csv');
                $writer->setUseBOM(false); // SJISには不要
                $writer->setDelimiter(',');
                $writer->setEnclosure('"');
                $writer->setLineEnding("\r\n");
                $writer->save($utf8_path);
                // SJISへ変換して保存し直す
                $sjis_path = Storage::disk('public')->path('nifuda/'.$directory_name.'/【'.sprintf('%02d', $make_file_count).'】'.$download_filename);
                $utf8_content = file_get_contents($utf8_path);
                $sjis_content = mb_convert_encoding($utf8_content, 'SJIS-win', 'UTF-8');
                file_put_contents($sjis_path, $sjis_content);
                // 一時ファイル削除
                unlink($utf8_path);
            }
        });
        return;
    }

    // ヤマト運輸
    public function createYamato($base_shipping_method, $orders, $shipping_group, $download_filename, $directory_name)
    {
        // 作成ファイル数をカウントする変数を初期化
        $make_file_count = 0;
        // チャンクサイズを指定
        $chunk_size = 1000;
        // レコードをチャンクごとに書き込む
        $orders->chunk($chunk_size, function ($orders) use ($base_shipping_method, $shipping_group, $download_filename, $directory_name, &$make_file_count) {
            // 作成ファイル数をカウントアップ
            $make_file_count++;
            // テンプレートを読み込む
            $templatePath = public_path('template/yamato.xlsx');
            $spreadsheet = IOFactory::load($templatePath);
            $worksheet = $spreadsheet->getActiveSheet();
            // データを書き込む位置を初期化
            $row = 2;
            // 受注の分だけループ処理
            foreach($orders as $order){
                // 配送先住所と荷送人住所からスペースを取り除く
                $ship_address = str_replace(array(" ", "　"), "", $order->ship_address);
                $shipper_address = str_replace(array(" ", "　"), "", $order->order_category->shipper->shipper_address);
                // 各情報を出力
                $worksheet->setCellValue('A'.$row, $order->order_control_id);   // 受注管理ID
                $worksheet->setCellValue('B'.$row, $base_shipping_method->setting_3);  // 送り状種類
                $worksheet->setCellValue('E'.$row, CarbonImmutable::parse($shipping_group->estimated_shipping_date)->format('Y/m/d'));  // 出荷予定日
                $worksheet->setCellValue('F'.$row, is_null($order->desired_delivery_date) ? '' : CarbonImmutable::parse($order->desired_delivery_date)->format('Y/m/d')); // 配送希望日
                $worksheet->setCellValue('G'.$row, DeliveryTimeZoneEnum::yamato_time_zone_get($order->desired_delivery_time));   // 配送希望時間
                $worksheet->setCellValue('I'.$row, $order->ship_tel);   // 配送先電話番号
                $worksheet->setCellValue('K'.$row, $order->ship_zip_code);  // 配送先郵便番号
                $worksheet->setCellValue('L'.$row, mb_substr($ship_address, 0, 21));    // 配送先住所1
                $worksheet->setCellValue('M'.$row, mb_substr($ship_address, 21, null));    // 配送先住所2
                $worksheet->setCellValue('P'.$row, $order->ship_name);  // 配送先名
                $worksheet->setCellValue('T'.$row, $order->order_category->shipper->shipper_tel); // 荷送人電話番号
                $worksheet->setCellValue('V'.$row, $order->order_category->shipper->shipper_zip_code); // 荷送人郵便番号
                $worksheet->setCellValue('W'.$row, mb_substr($shipper_address, 0, 16)); // 荷送人住所1
                $worksheet->setCellValue('X'.$row, mb_substr($shipper_address, 16, null));    // 配送先住所2
                $worksheet->setCellValue('Y'.$row, $order->order_category->shipper->shipper_name); // 荷送人名
                $worksheet->setCellValue('AB'.$row, 'コンタクトレンズ'); // 品名1
                $worksheet->setCellValue('AD'.$row, $order->order_no); // 品名2
                $worksheet->setCellValue('AG'.$row, $order->order_control_id); // 記事
                $worksheet->setCellValue('AN'.$row, $base_shipping_method->setting_1); // 請求先顧客コード
                $worksheet->setCellValue('AP'.$row, $base_shipping_method->setting_2); // 運賃管理番号
                $worksheet->setCellValue('BW'.$row, '荷主名'); // 検索キータイトル1
                $worksheet->setCellValue('BX'.$row, SystemEnum::CUSTOMER_NAME_EN); // 検索キー1
                $worksheet->setCellValue('BY'.$row, '出荷グループID'); // 検索キータイトル2
                $worksheet->setCellValue('BZ'.$row, sprintf('%02d', $order->shipping_group_id)); // 検索キー2
                $worksheet->setCellValue('CA'.$row, '出荷グループID連番'); // 検索キータイトル3
                $worksheet->setCellValue('CB'.$row, sprintf('%02d', $make_file_count)); // 検索キー3
                // データを書き込む位置をカウントアップ
                $row++;
            }
            // ファイルの保存先パスを取得
            $file_path = Storage::disk('public')->path('nifuda/'.$directory_name.'/【'.sprintf('%02d', $make_file_count).'】'.$download_filename);
            // Excelファイルを保存する
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save($file_path);
        });
        return;
    }

    // 荷札データ作成履歴を追加
    public function createNifudaCreateHistory($shipping_method_id, $directory_name)
    {
        NifudaCreateHistory::create([
            'shipping_group_id'     => session('filter_shipping_group_id'),
            'shipping_method_id'    => $shipping_method_id,
            'directory_name'        => $directory_name,
            'created_by'            => Auth::user()->user_no,
        ]);
        return;
    }
}