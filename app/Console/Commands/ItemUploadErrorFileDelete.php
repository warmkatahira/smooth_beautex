<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
// その他
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ItemUploadErrorFileDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'item_upload_error_file_delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Item Upload Error File Delete';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // ファイルを取得
        $files = File::glob(storage_path('app/public/export/item_upload_error/*.csv'));
        // ファイルの分だけループ処理
        foreach ($files as $file) {
            // ファイル名を取得
            $file_name = basename($file);
            // ファイル名から正規表現パターンで日付部分を抽出
            if(preg_match('/_(\d{4}-\d{2}-\d{2})/', $file_name, $matches)){
                $date = $matches[1];
                // 日付をDateTimeオブジェクトへ変換
                $date_time = \DateTime::createFromFormat('Y-m-d', $date);
                // 現在の日付よりも2日以上前であれば削除
                if($date_time < (new \DateTime())->sub(new \DateInterval('P2D'))){
                    // 削除処理
                    File::delete($file);
                }
            }
        }
    }
}
