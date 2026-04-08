<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
// その他
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BackupDbDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup_db_delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup Db Delete';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // db_backup_normalディスクを取得
        $disk = Storage::disk('db_backup_normal');
        // APP_NAMEのディレクトリ配下のファイル一覧を取得
        $files = $disk->files(config('app.name'));
        // 60日以上前のファイルを削除
        $this->deleteEnter($disk, $files);
    }

    // 60日以上前のファイルを削除
    private function deleteEnter($disk, $files)
    {
        // バックアップファイルの分だけループ処理
        foreach($files as $file){
            // ファイル名を取得
            $file_name = basename($file);
            // ファイル名から正規表現パターンで日付部分を抽出（yyyy-mm-dd-hh-mm-ss形式）
            if(preg_match('/(\d{4}-\d{2}-\d{2})-(\d{2}-\d{2}-\d{2})/', $file_name, $matches)){
                // 正規表現の1番目のキャプチャグループ（yyyy-mm-dd）を取得
                $date = $matches[1];
                // 日付文字列をDateTimeオブジェクトへ変換
                $date_time = \DateTime::createFromFormat('Y-m-d', $date);
                // 現在日時から60日を引いた日付よりも古いファイルを削除対象とする
                if($date_time < (new \DateTime())->sub(new \DateInterval('P60D'))->setTime(0, 0, 0)){
                    // 削除前にログを出力
                    Log::channel('cron')->info('Backup File Delete', ['file_name' => $file_name]);
                    // ファイルを削除
                    $disk->delete($file);
                }
            }
        }
    }
}
