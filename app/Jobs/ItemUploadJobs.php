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
use App\Models\ItemImport;
use App\Models\Job;
use App\Models\ItemUploadHistory;
// 列挙
use App\Enums\ItemUploadEnum;
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

    public $timeout = 600;              // 最大実行時間を120秒に設定
    public $user_no;                    // プロパティの定義
    public $save_file_full_path;        // プロパティの定義
    public $upload_original_file_name;  // プロパティの定義
    public $upload_type;                // プロパティの定義

    /**
     * Create a new job instance.
     */
    public function __construct($user_no, $save_file_full_path, $upload_original_file_name, $upload_type)
    {
        $this->user_no = $user_no;
        $this->save_file_full_path = $save_file_full_path;
        $this->upload_original_file_name = $upload_original_file_name;
        $this->upload_type = $upload_type;
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
            'user_no' => $this->user_no,
            'upload_file_path' => $this->save_file_full_path,
        ]);
        // ジョブを管理するテーブルにレコードを追加
        $item_upload_history = ItemUploadHistory::create([
            'job_id' => $job_id,
            'user_no' => $this->user_no,
            'upload_target' => ItemUploadEnum::UPLOAD_TARGET_ITEM,
            'upload_file_path' => $this->save_file_full_path,
            'upload_file_name' => $this->upload_original_file_name,
            'upload_type' => $this->upload_type,
        ]);
        // 処理タイプを変数にセット
        $upload_type = $this->upload_type;
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
            $proc_count = DB::transaction(function () use ($headers, $upload_type, $chunk_size, $chunks, $nowDate, $item_upload_history){
                // チャンク毎のループ処理
                foreach ($chunks as $chunk_index => $chunk){
                    // 追加するデータを配列に格納（同時にバリデーションも実施）
                    $data = $this->setArrayImportData($chunk, $headers, $chunk_size, $chunk_index);
                    // バリデーションエラーがある場合
                    if(count(array_filter($data['validation_error'])) != 0){
                        throw new ItemUploadException('データが正しくない為、アップロードできませんでした。', $data['validation_error'], $nowDate, $item_upload_history);
                    }
                    // item_importsテーブルへ追加
                    $this->createArrayImportData($data['create_data']);
                }
                // itemsテーブルへ追加と更新処理
                return $this->procCreateAndUpdate($headers, $upload_type);
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

    public function setArrayImportData($chunk, $headers, $chunk_size, $chunk_index)
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
        $validation_error = $this->commonValidation($create_data, $headers, $chunk_size, $chunk_index);
        // エラーメッセージがあればバリデーションエラーを配列に格納
        if(!empty($validation_error)){
            return compact('validation_error');
        }
        return compact('create_data', 'validation_error');
    }

    public function valueAdjustment($key, $value)
    {
        // 特定のキーのみ値の調整を行う
        switch ($key){
            case '商品コード':
            case '商品JANコード':
            case '代表JANコード':
                // 半角・全角スペースを取り除いている
                $adjustment_value = str_replace(array(" ", "　", "'"), "", $value);
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

    public function commonValidation($params, $headers, $chunk_size, $chunk_index)
    {
        // ルールを格納する配列をセット
        $rules = [];
        // バリデーションルールを定義
        foreach($headers as $column){
            switch ($column){
                case 'item_jan_code':
                    $rules += ['*.'.$column => 'required|max:13'];
                    break;
                case 'item_name':
                    $rules += ['*.'.$column => 'required|max:255'];
                    break;
                case 'item_category':
                    $rules += ['*.'.$column => 'nullable|max:20'];
                    break;
                case 'model_jan_code':
                    $rules += ['*.'.$column => 'nullable|max:13'];
                    break;
                case 'exp_start_position':
                    $rules += ['*.'.$column => 'nullable|integer|min:1'];
                    break;
                case 'lot_1_start_position':
                    $rules += ['*.'.$column => 'required_with:*.lot_1_length|nullable|integer|min:1'];
                    break;
                case 'lot_1_length':
                    $rules += ['*.'.$column => 'required_with:*.lot_1_start_position|nullable|integer|min:1'];
                    break;
                case 'lot_2_start_position':
                    $rules += ['*.'.$column => 'required_with:*.lot_2_length|nullable|integer|min:1'];
                    break;
                case 'lot_2_length':
                    $rules += ['*.'.$column => 'required_with:*.lot_2_start_position|nullable|integer|min:1'];
                    break;
                case 's_power_code':
                    $rules += ['*.'.$column => 'required_with:*.model_jan_code|nullable|integer|min:1'];
                    break;
                case 's_power_code_start_position':
                    $rules += ['*.'.$column => 'required_with:*.model_jan_code|nullable|integer|min:1'];
                    break;
                case 'is_stock_managed':
                    $rules += ['*.'.$column => 'required|boolean'];
                    break;
                case 'sort_order':
                    $rules += ['*.'.$column => 'nullable|integer|min:1'];
                    break;
                default:
                    break;
            }
        }
        // バリデーションエラーメッセージを定義
        $messages = [
            'required'                                      => ':attributeは必須です。',
            'max'                                           => ':attribute（:input）は:max文字以内で入力して下さい。',
            'boolean'                                       => ':attribute（:input）が正しくありません。',
            'exists'                                        => ':attribute（:input）はシステムに存在しません。',
            'min'                                           => ':attribute（:input）は:min以上で入力して下さい。',
            'integer'                                       => ':attribute（:input）は数値で入力して下さい。',
            '*.lot_2_start_position.required_with'          => 'LOT2桁数が入力されている場合、:attributeは必須です。',
            '*.lot_2_length.required_with'                  => 'LOT2開始位置が入力されている場合、:attributeは必須です。',
            '*.s_power_code.required_with'                  => '代表JANコードが入力されている場合、:attributeは必須です。',
            '*.s_power_code_start_position.required_with'   => '代表JANコードが入力されている場合、:attributeは必須です。',
        ];
        // バリデーションエラー項目を定義
        $attributes = [
            '*.item_jan_code'               => '商品JANコード',
            '*.item_name'                   => '商品名',
            '*.item_category'               => '商品カテゴリ',
            '*.model_jan_code'              => '代表JANコード',
            '*.exp_start_position'          => 'EXP開始位置',
            '*.lot_1_start_position'        => 'LOT1開始位置',
            '*.lot_1_length'                => 'LOT1桁数',
            '*.lot_2_start_position'        => 'LOT2開始位置',
            '*.lot_2_length'                => 'LOT2桁数',
            '*.s_power_code'                => 'S-POWERコード',
            '*.s_power_code_start_position' => 'S-POWERコード開始位置',
            '*.is_stock_managed'            => '在庫管理',
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
                'エラー行数' => ($key_explode[0] + 2) + ($chunk_size * $chunk_index) . '行目',
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

    public function procCreateAndUpdate($headers, $upload_type)
    {
        // +-+-+-+-+-+-+-+-+-   商品コードがitemsテーブルに存在しない場合は、追加処理を行う   +-+-+-+-+-+-+-+-+-
        // item_importsテーブルにしか存在していないレコードを取得(商品マスタに追加するカラムだけ取得)
        if($upload_type === ItemUploadEnum::UPLOAD_TYPE_CREATE){
            // itemsに存在しないレコードを取得
            $create_item = ItemImport::doesntHave('item')->select(array_map(function ($column){
                return $column;
            }, $headers))->get()->toArray();
            // itemsテーブルに追加
            Item::upsert($create_item, 'item_id');
            return count($create_item);
        }
        // +-+-+-+-+-+-+-+-+-   商品コードがitemsテーブルに存在する場合は、更新処理を行う   +-+-+-+-+-+-+-+-+-
        // itemsテーブルとitem_importsテーブルを結合して更新に必要なカラムを取得（結合した結果、どっちのテーブルにも存在しているデータ）
        if($upload_type === ItemUploadEnum::UPLOAD_TYPE_UPDATE){
            // itemsにするレコードを取得
            $update_item = Item::join('item_imports', 'item_imports.item_code', 'items.item_code')->select(array_map(function ($column){
                return 'item_imports.' . $column;
            }, $headers))->lockForUpdate()->get()->toArray();
            // レコードの分だけループ処理
            foreach($update_item as $item){
                // パラメータを格納する配列を初期化
                $param = [];
                // パラメータの分だけループ処理
                foreach($item as $key => $value){
                    // キーがセット商品コード以外の場合
                    if($key != 'item_code'){
                        // 配列にパラメータを格納
                        $param[$key] = $value;
                    }
                }
                // 商品コードを指定して更新
                Item::getSpecifyByItemCode($item['item_code'])->update($param);
            }
            return count($update_item);
        }
        return;
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
        $header = ['エラー行数', 'エラー内容'];
        $csvContent = "\xEF\xBB\xBF" . implode(',', $header) . "\n";
        // チャンク毎のループ処理
        foreach ($chunks as $chunk){
            // レコード毎のループ処理
            foreach ($chunk as $item){
                // CSV形式で内容をセット
                $row = [$item['エラー行数'], $item['エラー内容']];
                $csvContent .= implode(',', $row) . "\n";
            }
        }
        // ファイルに出力
        file_put_contents($csvFilePath, $csvContent);
        return;
    }
}