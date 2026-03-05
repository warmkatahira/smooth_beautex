<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('item_upload_histories', function (Blueprint $table){
            $table->increments('item_upload_history_id');
            $table->unsignedInteger('job_id');
            $table->unsignedInteger('user_no')->nullable();
            $table->string('upload_target', 10);
            $table->text('upload_file_path')->nullable();
            $table->string('upload_file_name')->nullable();
            $table->string('error_file_name')->nullable();
            $table->string('upload_type', 10);
            $table->string('status', 5)->default('処理中');
            $table->string('message', 50)->nullable();
            $table->timestamps();
            // 外部キー
            $table->foreign('user_no')->references('user_no')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_upload_histories');
    }
};
