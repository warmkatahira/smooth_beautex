<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutoProcessCondition extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'auto_process_condition_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'auto_process_id',
        'column_name',
        'operator',
        'value',
    ];
}
