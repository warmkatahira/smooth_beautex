<?php

namespace App\Http\Controllers\SystemAdmin\Holiday;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\Holiday;
// その他
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;

class NationalHolidayController extends Controller
{
    public function get_api(Request $request)
    {
        // 取得する西暦年を変数に格納
        $year = $request->input('get_year');
        // APIのURL
        $url = "https://holidays-jp.github.io/api/v1/{$year}/date.json";
        try {
            // 指定したURLにHTTP GETリクエストを送信
            $response = Http::get($url);
            // レスポンスが正常（HTTP 200 OK）の場合
            if($response->ok()){
                // JSON形式のレスポンスを配列に変換
                $holidays = $response->json();
                // $dataにテーブルへ追加する用の情報に整理して格納
                $data = collect($holidays)->map(function($name, $date) {
                    return [
                        'date' => $date,
                        'name' => $name,
                        'is_national' => 1,
                    ];
                })->values()->toArray();
                // テーブルへ追加
                Holiday::upsert($data, 'date');
            }else{
                throw new \Exception('国民の祝日の取得に失敗しました。');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => $year.'年の国民の祝日を取得しました。',
        ]);
    }
}