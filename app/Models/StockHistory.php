<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockHistory extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'stock_history_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'user_no',
        'stock_history_category_id',
        'comment',
    ];
    // usersテーブルとのリレーション
    public function user()
    {
        return $this->belongsTo(User::class, 'user_no', 'user_no');
    }
    // stock_history_categoriesテーブルとのリレーション
    public function stock_history_category()
    {
        return $this->belongsTo(StockHistoryCategory::class, 'stock_history_category_id', 'stock_history_category_id');
    }
    // ダウンロード時のヘッダーを定義
    public static function downloadHeader()
    {
        return [
            '履歴日',
            '履歴時間',
            '区分',
            '実行ユーザー',
            '倉庫名',
            '商品コード',
            '商品JANコード',
            '商品名',
            '商品カテゴリ1',
            '商品カテゴリ2',
            'LOT',
            'EXP',
            '数量',
            'コメント',
        ];
    }
}
