<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutoProcessOrderItem extends Model
{
    /**
        * 子（AutoProcessOrderItem）が更新・追加・削除されたときに、
        * 親（AutoProcess）の updated_at も自動で更新する
    */
    protected $touches = ['auto_process'];
    // 主キーカラムを変更
    protected $primaryKey = 'auto_process_order_item_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'auto_process_id',
        'order_item_code',
        'order_item_name',
        'order_quantity',
    ];
    // auto_processesテーブルとのリレーション
    public function auto_process()
    {
        return $this->belongsTo(AutoProcess::class, 'auto_process_id', 'auto_process_id');
    }
}
