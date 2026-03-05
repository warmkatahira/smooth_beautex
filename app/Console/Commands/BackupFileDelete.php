<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
// その他
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BackupFileDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup_file_delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup File Delete';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // ディレクトリ名を配列に格納
        $directories = [
            'shukka_jisseki',
            'tanaoroshi',
            'zaiko',
            'zaiko_csv',
        ];
        // ディレクトリ分だけループ処理
        foreach($directories as $directory){
            // 保存場所とファイルの情報を取得
            $disk = Storage::disk('file_backup');
            $files = $disk->files(env('APP_NAME').'/'.$directory);
            // 削除
            $this->deleteEnter($disk, $files);
        }
    }

    public function deleteEnter($disk, $files)
    {
        // ファイルの分だけループ処理
        foreach ($files as $file) {
            // ファイル名を取得
            $file_name = basename($file);
            // ファイル名から正規表現パターンで日付部分を抽出
            if(preg_match('/(\d{4}-\d{2}-\d{2})-(\d{2}-\d{2}-\d{2})/', $file_name, $matches)){
                $date = $matches[1];
                // 日付をDateTimeオブジェクトへ変換
                $date_time = \DateTime::createFromFormat('Y-m-d', $date);
                // 現在の日付よりも60日以上前であれば削除
                if ($date_time < (new \DateTime())->sub(new \DateInterval('P60D'))) {
                    // ログを出力（削除する前でないとダメなのでここ）
                    Log::channel('cron')->info('Backup File Delete', ['file_name' => $file_name]);
                    // 削除処理
                    $disk->delete($file);
                }
            }
        }
        return;
    }
}