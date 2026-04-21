<?php

namespace App\Http\Controllers\SavedFilter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\SavedFilter;
// リクエスト
use App\Http\Requests\Filter\SavedFilterCreateRequest;
// その他
use Illuminate\Support\Facades\Auth;

class SavedFilterController extends Controller
{
    // 一覧取得
    public function index(Request $request)
    {
        $filters = SavedFilter::forUser()->forPage($request->filter_page)->get();
        return response()->json($filters);
    }

    // 作成
    public function create(SavedFilterCreateRequest $request)
    {
        $filter = SavedFilter::create([
            'user_no'           => Auth::user()->user_no,
            'filter_page'       => $request->filter_page,
            'filter_name'       => $request->filter_name,
            'filter_conditions' => $request->filter_conditions,
        ]);
        return response()->json($filter);
    }

    // 削除
    public function delete(Request $request)
    {
        $savedFilter = SavedFilter::findOrFail($request->saved_filter_id);
        abort_if($savedFilter->user_no !== Auth::user()->user_no, 403);
        $savedFilter->delete();
        return response()->json(['message' => 'deleted']);
    }
}