<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
// モデル
use App\Models\NifudaCreateHistory;
// その他
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\CarbonImmutable;

class NifudaIssueDirectoryDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nifuda_issue_delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Nifuda Issue Delete';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // +-+-+-+-+-+-+-   荷札データ発行履歴削除   +-+-+-+-+-+-+-
        // 現在の日付から5日前を取得
        $date = CarbonImmutable::now()->subDays(5);
        // 荷札データ発行履歴で作成日が5日前より古いレコードを削除
        NifudaCreateHistory::where('created_at', '<', $date)->delete();
        // +-+-+-+-+-+-+-   ディレクトリ削除   +-+-+-+-+-+-+-
        // 配下のディレクトリを取得
        $directories = Storage::disk('public')->directories('nifuda');
        // ディレクトリの分だけループ処理
        foreach($directories as $directory){
            // ディレクトリ名から正規表現パターンで日付部分を抽出
            if(preg_match('/_(\d{4}-\d{2}-\d{2})/', $directory, $matches)){
                $date = $matches[1];
                // 日付をDateTimeオブジェクトへ変換
                $date_time = \DateTime::createFromFormat('Y-m-d', $date);
                // 現在の日付よりも5日より前であれば削除
                if($date_time < (new \DateTime())->sub(new \DateInterval('P5D'))){
                    // 削除処理
                    Storage::disk('public')->deleteDirectory($directory);
                }
            }
        }
    }
}