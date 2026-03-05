<?php

namespace App\Http\Controllers\SystemAdmin\Holiday;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\Holiday;
// その他
use Carbon\CarbonImmutable;

class HolidayController extends Controller
{
    public function index()
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '祝日']);
        // 祝日を取得
        $holidays = Holiday::getAll()->get();
        // 年ごとにグループ化
        $holidays_by_year = $holidays->groupBy(function($holiday) {
            return CarbonImmutable::parse($holiday->date)->year;
        });
        // 年を降順にソート
        $holidays_by_year = $holidays_by_year->sortByDesc(function($group, $year) {
            return $year;
        });
        return view('system_admin.holiday.index')->with([
            'holidays_by_year' => $holidays_by_year,
        ]);
    }
}