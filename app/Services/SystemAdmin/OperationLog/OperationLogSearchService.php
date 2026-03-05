<?php

namespace App\Services\SystemAdmin\OperationLog;

// 列挙
use App\Enums\OperationLogEnum;
use App\Enums\SystemEnum;
// その他
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class OperationLogSearchService
{
    // セッションを削除
    public function deleteSession()
    {
        // セッションを削除
        session()->forget([
            'search_operation_date_from',
            'search_operation_date_to',
        ]);
        return;
    }

    // セッションに検索条件を格納
    public function setSearchCondition($request)
    {
        // 変数が存在しない場合は検索が実行されていないので、初期条件をセット
        if(!isset($request->search_type)){
            session(['search_operation_date_from' => CarbonImmutable::now()->toDateString()]);
            session(['search_operation_date_to' => CarbonImmutable::now()->toDateString()]);
        }
        // 「search」なら検索が実行されているので、検索条件をセット
        if($request->search_type === 'search'){
            session(['search_operation_date_from' => $request->search_operation_date_from]);
            session(['search_operation_date_to' => $request->search_operation_date_to]);
        }
        return;
    }

    // ログ情報を取得
    public function getLogContent()
    {
        // ログファイルを全て取得
        $disk = Storage::disk('operation_logs');
        $all_files = $disk->files();
        // ログ情報を格納する配列を初期化
        $log_contents = [];
        // ファイルの分だけループ処理
        foreach ($all_files as $file){
            // ファイル名の条件に合致した場合
            if(preg_match('/operation-(\d{4}-\d{2}-\d{2})\.log/', $file, $matches)){
                // ファイル名から日付を取得
                $file_date = CarbonImmutable::parse($matches[1]);
                // ファイル名の日付が検索条件の期間内であれば、情報を取得
                if($file_date->between(session('search_operation_date_from'), session('search_operation_date_to'))){
                    $file_contents = Storage::disk('operation_logs')->get($file);
                    // 正規表現でログの各フィールドを抽出
                    $pattern = '/\[(.*?)\] \w+\.INFO: User No: (\d+), User Name: ([^,]+), IP Address: ([^,]+), Method: ([^,]+), Path: ([^,]+), Params: (.*)/';
                    preg_match_all($pattern, $file_contents, $matches, PREG_SET_ORDER);
                    // レコードの分だけループ処理
                    foreach ($matches as $match){
                        // datetimeをdateとtimeに分割
                        list($operation_date, $operation_time) = explode(' ', $match[1]);
                        // 配列に格納
                        $log_contents[] = collect([
                            'operation_date'    => $operation_date,
                            'operation_time'    => $operation_time,
                            'user_no'           => $match[2],
                            'user_name'         => $match[3],
                            'ip_address'        => $match[4],
                            'method'            => $match[5],
                            'path'              => OperationLogEnum::get_path_jp($match[6]),
                            'param'             => substr($match[7], 0, 2) == '[]' ? null : $match[7],
                        ]);
                    }
                }
            }
        }
        return collect($log_contents);
    }

    // ページネーションを実施
    public function setPagination($query)
    {
        // 1ページあたりの表示件数を取得（列挙から）
        $perPage = SystemEnum::PAGINATE_OPERATION_LOG;
        // 現在のページ番号を取得（URLの ?page= などから自動で解決）
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        // 対象ページに表示するアイテムのみを抽出
        $items = $query->slice(($currentPage - 1) * $perPage, $perPage)->values();
        // LengthAwarePaginator を使ってページネーションのインスタンスを作成
        return new LengthAwarePaginator(
            $items,                                 // 抽出されたページのアイテム
            $query->count(),                        // 全件数
            $perPage,                               // 1ページあたりの件数
            $currentPage,                           // 現在のページ
            [                                       // ページリンクの生成に使うオプション
                'path' => request()->url(),         // 現在のURLを基準に
                'query' => request()->query(),      // クエリ文字列を引き継ぐ（例：検索条件）
            ]
        );
    }
}