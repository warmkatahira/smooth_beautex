<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemUploadHistory extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'item_upload_history_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'job_id',
        'user_no',
        'upload_target',
        'upload_file_path',
        'upload_file_name',
        'error_file_name',
        'upload_type',
        'status',
        'message',
    ];
    // 全てのレコードを取得
    public static function getAll()
    {
        return self::orderBy('item_upload_history_id', 'desc');
    }
    // usersテーブルとのリレーション
    public function user()
    {
        return $this->belongsTo(User::class, 'user_no', 'user_no');
    }
    // 「upload_type」に基づいて、日本語を返すアクセサ
    public function getUploadTypeTextAttribute(): string
    {
        switch ($this->upload_type){
            case 'create':
                return '追加';
            case 'update':
                return '更新';
            default:
                return '不明';
        }
    }
    // 「upload_target」に基づいて、日本語を返すアクセサ
    public function getUploadTargetTextAttribute(): string
    {
        switch ($this->upload_target){
            case 'item':
                return '商品';
            default:
                return '不明';
        }
    }
}
