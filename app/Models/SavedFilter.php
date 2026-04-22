<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// その他
use Illuminate\Support\Facades\Auth;

class SavedFilter extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'saved_filter_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'user_no',
        'filter_page',
        'filter_name',
        'filter_conditions',
    ];

    public function scopeForUser($query)
    {
        return $query->where('user_no', Auth::user()->user_no);
    }

    public function scopeForPage($query, $page)
    {
        return $query->where('filter_page', $page);
    }
}
