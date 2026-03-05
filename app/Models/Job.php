<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'user_no',
        'upload_file_path',
        'updated_at',
    ];
}
