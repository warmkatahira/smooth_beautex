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
use App\Enums\DeliveryTimeZoneChangeEnum;
use App\Enums\SystemEnum;
use App\Enums\ShippingMethodEnum;
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
        $orders = Order::with('shipper')
                    ->with('order_items')
                    ->where('shipping_group_id', session('search_shipping_group_id'))
                    ->where('shipping_method_id', $shipping_method_id)
                    ->select('orders.*')
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
        $shipping_group = ShippingGroup::getSpecify(session('search_shipping_group_id'))->first();
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
            // ファイル名を取得
            $download_filename = '【'.$shipping_method->delivery_company_and_shipping_method.'】荷札データ_'.$nowDate->isoFormat('Y年MM月DD日HH時mm分ss秒').'.'.$base_shipping_method->e_hiden_version->file_extension;
            // 国内用
            $this->createSagawaForJp($base_shipping_method, $orders, $shipping_group, $download_filename, $directory_name);
        }
        // ヤマト運輸
        if($shipping_method->delivery_company_id === DeliveryCompanyEnum::YAMATO){
            $download_filename = '【'.$shipping_method->delivery_company_and_shipping_method.'】荷札データ_'.$nowDate->isoFormat('Y年MM月DD日HH時mm分ss秒').'.xlsx';
            $this->createYamato($base_shipping_method, $orders, $shipping_group, $download_filename, $directory_name);
        }
        return $directory_name;
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
            $templatePath = public_path('template/'.$base_shipping_method->e_hiden_version->file_name);
            $spreadsheet = IOFactory::load($templatePath);
            $worksheet = $spreadsheet->getActiveSheet();
            // データを書き込む位置を初期化
            $row = $base_shipping_method->e_hiden_version->data_start_row;
            // 受注の分だけループ処理
            foreach($orders as $order){
                // 配送先住所と荷送人住所からスペースを取り除く
                $ship_address = str_replace(array(" ", "　"), "", $order->ship_address);
                $shipper_address = str_replace(array(" ", "　"), "", $order->shipper->shipper_address);
                // 各情報を出力
                $worksheet->setCellValue('C'.$row, $order->ship_tel);   // 配送先電話番号
                $worksheet->setCellValue('D'.$row, $order->ship_zip_code);  // 配送先郵便番号
                $worksheet->setCellValue('E'.$row, $ship_address);    // 配送先住所
                $worksheet->setCellValue('H'.$row, $order->ship_name.$order->ship_staff_name);  // 配送先名
                $worksheet->setCellValue('J'.$row, $order->order_control_id);   // 受注管理ID
                $worksheet->setCellValue('K'.$row, $base_shipping_method->setting_1); // お客様コード
                $worksheet->setCellValue('Q'.$row, $base_shipping_method->setting_1); // ご依頼主コード
                $worksheet->setCellValue('R'.$row, $order->shipper->shipper_tel); // ご依頼主電話番号
                $worksheet->setCellValue('S'.$row, $order->shipper->shipper_zip_code); // ご依頼主郵便番号
                $worksheet->setCellValue('T'.$row, $shipper_address); // ご依頼主住所
                $worksheet->setCellValue('V'.$row, $order->shipper->shipper_name); // ご依頼主名
                $worksheet->setCellValue('Y'.$row, $order->order_no); // 品名1
                $worksheet->setCellValue('Z'.$row, 'コンタクトレンズ'); // 品名2
                $worksheet->setCellValue('AS'.$row, is_null($order->desired_delivery_date) ? '' : CarbonImmutable::parse($order->desired_delivery_date)->format('Y/m/d')); // 配送希望日
                $worksheet->setCellValue('AT'.$row, DeliveryTimeZoneChangeEnum::sagawa_time_zone_get($order->desired_delivery_time));   // 配送希望時間
                $worksheet->setCellValue('AZ'.$row, '011'); // 指定シール1(取注)
                $worksheet->setCellValue('BA'.$row, DeliveryTimeZoneChangeEnum::sagawa_seal_code_get($base_shipping_method->e_hiden_version, $order->desired_delivery_date, $order->desired_delivery_time)); // 指定シール2(日時指定)
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
                $shipper_address = str_replace(array(" ", "　"), "", $order->shipper->shipper_address);
                // 各情報を出力
                $worksheet->setCellValue('A'.$row, $order->order_control_id);   // 受注管理ID
                $worksheet->setCellValue('B'.$row, $base_shipping_method->setting_3);  // 送り状種類
                $worksheet->setCellValue('E'.$row, CarbonImmutable::parse($shipping_group->estimated_shipping_date)->format('Y/m/d'));  // 出荷予定日
                $worksheet->setCellValue('F'.$row, is_null($order->desired_delivery_date) ? '' : CarbonImmutable::parse($order->desired_delivery_date)->format('Y/m/d')); // 配送希望日
                $worksheet->setCellValue('G'.$row, DeliveryTimeZoneChangeEnum::yamato_time_zone_get($order->desired_delivery_time));   // 配送希望時間
                $worksheet->setCellValue('I'.$row, $order->ship_tel);   // 配送先電話番号
                $worksheet->setCellValue('K'.$row, $order->ship_zip_code);  // 配送先郵便番号
                $worksheet->setCellValue('L'.$row, mb_substr($ship_address, 0, 21));    // 配送先住所1
                $worksheet->setCellValue('M'.$row, mb_substr($ship_address, 21, null));    // 配送先住所2
                $worksheet->setCellValue('P'.$row, $order->ship_name);  // 配送先名
                $worksheet->setCellValue('T'.$row, $order->shipper->shipper_tel); // 荷送人電話番号
                $worksheet->setCellValue('V'.$row, $order->shipper->shipper_zip_code); // 荷送人郵便番号
                $worksheet->setCellValue('W'.$row, mb_substr($shipper_address, 0, 16)); // 荷送人住所1
                $worksheet->setCellValue('X'.$row, mb_substr($shipper_address, 16, null));    // 配送先住所2
                $worksheet->setCellValue('Y'.$row, $order->shipper->shipper_name); // 荷送人名
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
            'shipping_group_id'     => session('search_shipping_group_id'),
            'shipping_method_id'    => $shipping_method_id,
            'directory_name'        => $directory_name,
            'created_by'            => Auth::user()->user_no,
        ]);
        return;
    }
}