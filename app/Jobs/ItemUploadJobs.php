<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
// モデル
use App\Models\Item;
use App\Models\Stock;
use App\Models\ItemImport;
use App\Models\Job;
use App\Models\ItemUploadHistory;
use App\Models\OrderItem;
// 列挙
use App\Enums\ItemUploadEnum;
use App\Enums\OrderStatusEnum;
// 例外
use App\Exceptions\ItemUploadException;
// その他
use Rap2hpoutre\FastExcel\FastExcel;
use Throwable;
use Illuminate\Support\Facades\Validator;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class ItemUploadJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 600;              // 最大実行時間を600秒に設定
    public $user_no;                    // プロパティの定義
    public $save_file_full_path;        // プロパティの定義
    public $upload_original_file_name;  // プロパティの定義
    public $upload_type;                // プロパティの定義
    public $file_type;                  // プロパティの定義

    /**
     * Create a new job instance.
     */
    public function __construct($user_no, $save_file_full_path, $upload_original_file_name, $upload_type, $file_type)
    {
        $this->user_no = $user_no;
        $this->save_file_full_path = $save_file_full_path;
        $this->upload_original_file_name = $upload_original_file_name;
        $this->upload_type = $upload_type;
        $this->file_type = $file_type;
    }

    /* public function queue($queue, $job)
    {
        $queue->pushOn('item_upload', $job);
    } */

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // 現在のjob_idを取得
        $job_id = $this->job->getJobId();
        // カラムにパラメータの値を更新
        Job::where('id', $job_id)->update([
            'user_no'           => $this->user_no,
            'upload_file_path'  => $this->save_file_full_path,
        ]);
        // ジョブを管理するテーブルにレコードを追加
        $item_upload_history = ItemUploadHistory::create([
            'job_id'            => $job_id,
            'user_no'           => $this->user_no,
            'upload_target'     => ItemUploadEnum::UPLOAD_TARGET_ITEM,
            'upload_file_path'  => $this->save_file_full_path,
            'upload_file_name'  => $this->upload_original_file_name,
            'upload_type'       => $this->upload_type,
        ]);
        // 処理タイプを変数にセット
        $upload_type = $this->upload_type;
        // ファイルタイプを変数にセット
        $file_type = $this->file_type;
        // 全データを取得
        $all_line = (new FastExcel)->import($this->save_file_full_path);
        // インポートしたデータのヘッダーを取得
        $data_header = array_keys(mb_convert_encoding($all_line[0], 'UTF-8', 'ASCII, JIS, UTF-8, SJIS-win'));
        // ヘッダーを日本語から英語に変換
        $headers = $this->changeHeaderEn($data_header);
        // ファイルのデータを配列化（これをしないとチャンク処理できない）
        $all_line = $all_line->toArray();
        // チャンクサイズの設定
        $chunk_size = 500;
        // チャンクサイズ毎に分割
        $chunks = array_chunk($all_line, $chunk_size);
        // 現在の日時を取得
        $nowDate = CarbonImmutable::now();
        // テーブルをクリア
        $this->clearItemImport();
        try {
            // 先に全チャンク分バリデーションを実施
            $all_errors = [];
            $all_data = [];
            foreach ($chunks as $chunk_index => $chunk){
                $data = $this->setArrayImportData($chunk, $headers, $chunk_size, $chunk_index, $file_type);
                if(count(array_filter($data['validation_error'])) != 0){
                    $all_errors = array_merge($all_errors, $data['validation_error']);
                } else {
                    $all_data[] = $data['create_data'];
                }
            }
            // エラーがあれば終了
            if(!empty($all_errors)){
                throw new ItemUploadException('データが正しくない為、アップロードできませんでした。', $all_errors, $nowDate, $item_upload_history);
            }
            // エラーがなければDB書き込み
            $proc_count = DB::transaction(function () use ($headers, $upload_type, $all_data, $nowDate, $item_upload_history){
                foreach ($all_data as $create_data){
                    $this->createArrayImportData($create_data);
                }
                // itemsテーブルへ追加と更新処理
                return $this->procCreateAndUpdate($headers, $upload_type, $item_upload_history);
            });
        } catch (ItemUploadException $e){
            // 渡された内容を取得
            $validation_error = $e->getValidationError();
            $nowDate = $e->getNowDate();
            $item_upload_history = $e->getItemUploadHistory();
            // エラーファイルを作成してテーブルを更新
            $this->item_upload_error_export($validation_error, $nowDate, $item_upload_history, $e->getMessage());
            return;
        }
        // 完了フラグを更新
        ItemUploadHistory::where('item_upload_history_id', $item_upload_history->item_upload_history_id)->update([
            'status' => '完了',
            'message' => '処理件数：'.$proc_count.'件',
        ]);
    }

    public function changeHeaderEn($data_header)
    {
        // 1行のデータを格納する配列をセット
        $param = [];
        // 追加先テーブルのカラム名に合わせて配列を整理
        foreach($data_header as $header){
            // 英語カラムを定義している配列から取得
            $en_column = Item::column_en_change($header);
            // カラムが空ではない場合
            if($en_column != ''){
                // 配列に変換した英語カラムを格納
                $param[] = $en_column;
            }
        }
        return $param;
    }

    // テーブルをクリア
    public function clearItemImport()
    {
        // 追加先のテーブルをクリア
        ItemImport::query()->delete();
    }

    public function setArrayImportData($chunk, $headers, $chunk_size, $chunk_index, $file_type)
    {
        // 配列をセット
        $create_data = [];
        // 取得したレコードの分だけループ
        foreach ($chunk as $line){
            // UTF-8形式に変換した1行分のデータを取得
            $line = mb_convert_encoding($line, 'UTF-8', 'ASCII, JIS, UTF-8, SJIS-win');
            // 1行のデータを格納する配列をセット
            $param = [];
            // 追加先テーブルのカラム名に合わせて配列を整理
            foreach($line as $key => $value){
                // 英語カラムを定義している配列から取得
                $en_column = Item::column_en_change($key);
                // カラムが空ではない場合
                if($en_column != ''){
                    // 値の調整を行う
                    $adjustment_value = $this->valueAdjustment($key, $value);
                    // 配列に変換した英語カラムを格納
                    $param[$en_column] = $adjustment_value;
                }
            }
            // 追加用の配列に整理した情報を格納
            $create_data[] = $param;
        }
        // バリデーション（共通）
        $validation_error = $this->commonValidation($create_data, $headers, $chunk_size, $chunk_index, $file_type);
        return compact('create_data', 'validation_error');
    }

    public function valueAdjustment($key, $value)
    {
        // 特定のキーのみ値の調整を行う
        switch ($key){
            case '商品コード':
            case '商品JANコード':
            case '代表JANコード':
            case 'カラーID':
                // 半角・全角スペースを取り除いている
                $adjustment_value = str_replace(array(" ", "　", "'"), "", $value);
                break;
            case 'ロット管理':
                // 無効を「0」、有効を「1」に変換
                $adjustment_value = $value === '無効' ? 0 : ($value === '有効' ? 1 : $value);
                break;
            case '在庫管理':
                // 無効を「0」、有効を「1」に変換
                $adjustment_value = $value === '無効' ? 0 : ($value === '有効' ? 1 : $value);
                break;
            case '並び順':
                // 空なら「99999」をセットする
                $adjustment_value = $value === '' ? 99999 : $value;
                break;
            default:
                // 何もしない
                $adjustment_value = $value;
                break;
        }
        return $adjustment_value === '' ? null : $adjustment_value;
    }

    public function commonValidation($params, $headers, $chunk_size, $chunk_index, $file_type)
    {
        // ルールを格納する配列をセット
        $rules = [];
        // バリデーションルールを定義
        foreach($headers as $column){
            switch ($column){
                case 'item_jan_code':
                    $rules += ['*.'.$column => 'required|string|max:13'];
                    break;
                case 'item_name':
                    $rules += ['*.'.$column => 'required|string|max:255'];
                    break;
                case 'item_category_1':
                case 'item_category_2':
                case 'wearing_period':
                case 'quantity_per_box':
                case 'color_id':
                case 'manufacturer':
                case 'supplier':
                    $rules += ['*.'.$column => 'nullable|string|max:20'];
                    break;
                case 'brand':
                    $rules += ['*.'.$column => 'nullable|string|max:50'];
                    break;
                case 'color_row':
                    $rules += ['*.'.$column => 'nullable|integer|min:0|max:255'];
                    break;
                case 'is_lot_managed':
                    $rules += ['*.'.$column => 'required|boolean'];
                    break;
                case 'model_jan_code':
                    $rules += ['*.'.$column => 'nullable|string|max:13'];
                    break;
                case 'exp_start_position':
                    $rules += ['*.'.$column => 'nullable|integer|between:1,255'];
                    break;
                case 'lot_1_start_position':
                    $rules += ['*.'.$column => 'required_if:*.is_lot_managed,1|required_with:*.lot_1_length|nullable|integer|between:1,255'];
                    break;
                case 'lot_1_length':
                    $rules += ['*.'.$column => 'required_if:*.is_lot_managed,1|required_with:*.lot_1_start_position|nullable|integer|between:1,255'];
                    break;
                case 'lot_2_start_position':
                    $rules += ['*.'.$column => 'required_with:*.lot_2_length|nullable|integer|between:1,255'];
                    break;
                case 'lot_2_length':
                    $rules += ['*.'.$column => 'required_with:*.lot_2_start_position|nullable|integer|between:1,255'];
                    break;
                case 's_power_code':
                    $rules += ['*.'.$column => 'required_with:*.model_jan_code|nullable|integer|between:200,240'];
                    break;
                case 's_power_code_start_position':
                    $rules += ['*.'.$column => 'required_with:*.model_jan_code|nullable|integer|between:1,255'];
                    break;
                case 'is_stock_managed':
                    $rules += ['*.'.$column => 'required|boolean'];
                    break;
                case 'country_of_origin':
                case 'hs_code':
                    $rules += ['*.'.$column => 'nullable|string|max:10'];
                    break;
                case 'sort_order':
                    $rules += ['*.'.$column => 'required|integer|min:1'];
                    break;
                case 'item_weight_g':
                    $rules += ['*.'.$column => 'nullable|integer|min:0'];
                    break;
                default:
                    break;
            }
        }
        // バリデーションエラーメッセージを定義
        $messages = [
            'required'                                      => ':attributeは必須です。',
            'string'                                        => ":attributeは文字列で入力して下さい。",
            'max'                                           => ':attribute（:input）は:max文字以内で入力して下さい。',
            'boolean'                                       => ':attribute（:input）が正しくありません。',
            'exists'                                        => ':attribute（:input）はシステムに存在しません。',
            'min'                                           => ':attribute（:input）は:min以上で入力して下さい。',
            'integer'                                       => ':attribute（:input）は数値で入力して下さい。',
            'between'                                       => ":attributeは:minから:maxの間で入力して下さい。",
            '*.lot_1_start_position.required_if'            => 'ロット管理が有効の場合、:attributeは必須です。',
            '*.lot_1_length.required_if'                    => 'ロット管理が有効の場合、:attributeは必須です。',
            '*.lot_1_start_position.required_with'          => 'LOT1桁数が入力されている場合、:attributeは必須です。',
            '*.lot_1_length.required_with'                  => 'LOT1開始位置が入力されている場合、:attributeは必須です。',
            '*.lot_2_start_position.required_with'          => 'LOT2桁数が入力されている場合、:attributeは必須です。',
            '*.lot_2_length.required_with'                  => 'LOT2開始位置が入力されている場合、:attributeは必須です。',
            '*.s_power_code.required_with'                  => '代表JANコードが入力されている場合、:attributeは必須です。',
            '*.s_power_code_start_position.required_with'   => '代表JANコードが入力されている場合、:attributeは必須です。',
        ];
        // バリデーションエラー項目を定義
        $attributes = [
            '*.item_jan_code'               => '商品JANコード',
            '*.item_name'                   => '商品名',
            '*.item_category_1'             => '商品カテゴリ1',
            '*.item_category_2'             => '商品カテゴリ2',
            '*.is_lot_managed'              => 'ロット管理',
            '*.model_jan_code'              => '代表JANコード',
            '*.exp_start_position'          => 'EXP開始位置',
            '*.lot_1_start_position'        => 'LOT1開始位置',
            '*.lot_1_length'                => 'LOT1桁数',
            '*.lot_2_start_position'        => 'LOT2開始位置',
            '*.lot_2_length'                => 'LOT2桁数',
            '*.s_power_code'                => 'S-POWERコード',
            '*.s_power_code_start_position' => 'S-POWERコード開始位置',
            '*.is_stock_managed'            => '在庫管理',
            '*.country_of_origin'           => '原産国',
            '*.hs_code'                     => 'HSコード',
            '*.brand'                       => 'ブランド',
            '*.wearing_period'              => '装用期間',
            '*.quantity_per_box'            => '入数',
            '*.color_id'                    => 'カラーID',
            '*.color_row'                   => 'カラーROW',
            '*.manufacturer'                => 'メーカー',
            '*.supplier'                    => '仕入先',
            '*.item_weight_g'               => '商品重量',
            '*.sort_order'                  => '並び順',
        ];
        // バリデーション実施
        return $this->procValidation($params, $rules, $messages, $attributes, $chunk_size, $chunk_index);
    }

    public function procValidation($params, $rules, $messages, $attributes, $chunk_size, $chunk_index)
    {
        // 配列をセット
        $validation_error = [];
        // バリデーション実施
        $validator = Validator::make($params, $rules, $messages, $attributes);
        // バリデーションエラーの分だけループ
        foreach($validator->errors()->getMessages() as $key => $value){
            // 値を「.」で分割
            $key_explode = explode('.', $key);
            // メッセージを格納
            $validation_error[] = [
                '商品コード' => $params[$key_explode[0]]['item_code'] ?? '-',
                'エラー内容' => $value[0],
            ];
        }
        return $validation_error;
    }

    public function createArrayImportData($create_data)
    {
        // 追加用の配列に入っている情報をテーブルに追加
        ItemImport::insert($create_data);
    }

    public function procCreateAndUpdate($headers, $upload_type, $item_upload_history)
    {
        // +-+-+-+-+-+-+-+-+-   商品コードがitemsテーブルに存在しない場合は、追加処理を行う   +-+-+-+-+-+-+-+-+-
        // item_importsテーブルにしか存在していないレコードを取得(商品マスタに追加するカラムだけ取得)
        if($upload_type === ItemUploadEnum::UPLOAD_TYPE_CREATE){
            $chunk_size = 500;
            $proc_count = 0;
            ItemImport::doesntHave('item')
                ->select(array_merge(
                    ['item_import_id'],
                    array_map(function($col){
                        return DB::raw("item_imports.{$col} as {$col}");
                    }, $headers)
                ))
                ->chunkById($chunk_size, function($chunk) use (&$proc_count){
                    $create_item = collect($chunk)->map(function($item){
                        // (array)ではなくtoArray()を使う
                        $item = $item->toArray();
                        unset($item['item_import_id']);
                        return $item;
                    })->toArray();
                    Item::upsert($create_item, 'item_id');
                    $proc_count += count($create_item);
                }, 'item_import_id');
            return $proc_count;
        }
        // +-+-+-+-+-+-+-+-+-   商品コードがitemsテーブルに存在する場合は、更新処理を行う   +-+-+-+-+-+-+-+-+-
        // itemsテーブルとitem_importsテーブルを結合して更新に必要なカラムを取得（結合した結果、どっちのテーブルにも存在しているデータ）
        if($upload_type === ItemUploadEnum::UPLOAD_TYPE_UPDATE){
            // is_stock_managedが含まれている場合のみチェック
            if(in_array('is_stock_managed', $headers)){
                // 在庫管理が更新されようとしている商品コードを取得
                $conflicting_item_codes = Item::join('item_imports', 'item_imports.item_code', 'items.item_code')
                                                ->whereColumn('items.is_stock_managed', '!=', 'item_imports.is_stock_managed')
                                                ->pluck('items.item_code');
                // 更新されようとしている商品コードがある場合
                if($conflicting_item_codes->isNotEmpty()){
                    // 出荷完了前で今回の商品が含まれている受注が存在するか確認
                    $exists = OrderItem::whereIn('order_item_code', $conflicting_item_codes)
                                    ->whereHas('order', function ($query) {
                                        $query->where('order_status_id', '!=', OrderStatusEnum::SHUKKA_ZUMI);
                                    })
                                    ->exists();
                    // 受注が存在する場合
                    if($exists){
                        $error_data = $conflicting_item_codes->map(function($item_code){
                            return [
                                '商品コード' => $item_code,
                                'エラー内容' => '在庫管理が変更される商品が出荷完了前の受注に含まれているため、アップロードできませんでした。',
                            ];
                        })->toArray();
                        throw new ItemUploadException(
                            '在庫管理が変更される商品が出荷完了前の受注に含まれているため、アップロードできませんでした。',
                            $error_data,
                            CarbonImmutable::now(),
                            $item_upload_history
                        );
                    }
                    // 在庫が残っているか確認
                    $stock_exists_codes = Stock::join('items', 'items.item_id', 'stocks.item_id')
                                                ->whereIn('items.item_code', $conflicting_item_codes)
                                                ->where('stocks.total_stock', '>', 0)
                                                ->pluck('items.item_code');
                    // 在庫が残っている場合
                    if($stock_exists_codes->isNotEmpty()){
                        $error_data = $stock_exists_codes->map(function($item_code){
                            return [
                                '商品コード' => $item_code,
                                'エラー内容' => '在庫が残っているため、在庫管理を変更できませんでした。',
                            ];
                        })->toArray();
                        throw new ItemUploadException(
                            '在庫が残っているため、在庫管理を変更できませんでした。',
                            $error_data,
                            CarbonImmutable::now(),
                            $item_upload_history
                        );
                    }
                }
            }
            // is_lot_managedが含まれている場合のみチェック
            if(in_array('is_lot_managed', $headers)){
                // ロット管理が更新されようとしている商品コードを取得
                $conflicting_item_codes = Item::join('item_imports', 'item_imports.item_code', 'items.item_code')
                                                ->whereColumn('items.is_lot_managed', '!=', 'item_imports.is_lot_managed')
                                                ->pluck('items.item_code');
                // 更新されようとしている商品コードがある場合
                if($conflicting_item_codes->isNotEmpty()){
                    // 出荷完了前で今回の商品が含まれている受注が存在するか確認
                    $exists = OrderItem::whereIn('order_item_code', $conflicting_item_codes)
                                    ->whereHas('order', function ($query) {
                                        $query->where('order_status_id', '!=', OrderStatusEnum::SHUKKA_ZUMI);
                                    })
                                    ->exists();
                    // 受注が存在する場合
                    if($exists){
                        $error_data = $conflicting_item_codes->map(function($item_code){
                            return [
                                '商品コード' => $item_code,
                                'エラー内容' => 'ロット管理フラグが変更される商品が出荷完了前の受注に含まれているため、アップロードできませんでした。',
                            ];
                        })->toArray();
                        throw new ItemUploadException(
                            'ロット管理フラグが変更される商品が出荷完了前の受注に含まれているため、アップロードできませんでした。',
                            $error_data,
                            CarbonImmutable::now(),
                            $item_upload_history
                        );
                    }
                    // 在庫が残っているか確認
                    $stock_exists_codes = Stock::join('items', 'items.item_id', 'stocks.item_id')
                                            ->whereIn('items.item_code', $conflicting_item_codes)
                                            ->where('stocks.total_stock', '>', 0)
                                            ->pluck('items.item_code');
                    // 在庫が残っている場合
                    if($stock_exists_codes->isNotEmpty()){
                        $error_data = $stock_exists_codes->map(function($item_code){
                            return [
                                '商品コード' => $item_code,
                                'エラー内容' => '在庫が残っているため、ロット管理フラグを変更できませんでした。',
                            ];
                        })->toArray();
                        throw new ItemUploadException(
                            '在庫が残っているため、ロット管理フラグを変更できませんでした。',
                            $error_data,
                            CarbonImmutable::now(),
                            $item_upload_history
                        );
                    }
                }
            }
            // item_codeを除いた更新対象カラムを定義
            $update_columns = array_values(array_filter($headers, fn($col) => $col !== 'item_code'));
            $chunk_size = 500;
            $proc_count = 0;
            // チャンク単位で取得・UPDATE
            Item::join('item_imports', 'item_imports.item_code', 'items.item_code')
                    ->select(array_merge(
                        // chunkByIdのためにitems.item_idを追加
                        [DB::raw('items.item_id as item_id')],
                        array_map(function ($column){
                            return DB::raw("item_imports.{$column} as {$column}");
                        }, $headers)
                    ))
                    ->chunkById($chunk_size, function($chunk) use ($update_columns, &$proc_count){
                        DB::transaction(function() use ($chunk, $update_columns, &$proc_count){
                            // item_idを除いてupsert
                            $update_item = collect($chunk)->map(function($item){
                                // (array)$item ではなく toArray() を使う
                                $item = $item->toArray();
                                unset($item['item_id']);
                                return $item;
                            })->toArray();
                            Item::upsert($update_item, ['item_code'], $update_columns);
                            $proc_count += count($update_item);
                        });
                    }, 'item_id');
            return $proc_count;
        }
    }

    public function item_upload_error_export($validation_error, $nowDate, $item_upload_history, $message)
    {
        // チャンクサイズを設定
        $chunk_size = 500;
        // チャンクサイズ毎に分割
        $chunks = array_chunk($validation_error, $chunk_size);
        // ファイル名を設定
        $error_file_name = '商品アップロードエラー_'.$nowDate->format('Y-m-d H-i-s').'_'.$item_upload_history->user_no.'.csv';
        // 保存場所を設定
        $csvFilePath = storage_path('app/public/export/item_upload_error/'.$error_file_name);
        // エラーファイル名を更新
        ItemUploadHistory::where('item_upload_history_id', $item_upload_history->item_upload_history_id)->update([
            'error_file_name' => $error_file_name,
            'status' => '失敗',
            'message' => $message,
        ]);
        // ヘッダ行を書き込む
        $header = ['商品コード', 'エラー内容'];
        $csvContent = "\xEF\xBB\xBF" . implode(',', $header) . "\n";
        foreach ($chunks as $chunk){
            foreach ($chunk as $item){
                $row = [$item['商品コード'], $item['エラー内容']];
                $csvContent .= implode(',', $row) . "\n";
            }
        }
        // ファイルに出力
        file_put_contents($csvFilePath, $csvContent);
    }
}