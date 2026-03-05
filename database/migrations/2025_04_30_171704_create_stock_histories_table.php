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
        Schema::create('stock_histories', function (Blueprint $table){
            $table->increments('stock_history_id');
            $table->unsignedInteger('user_no')->nullable();
            $table->unsignedInteger('stock_history_category_id');
            $table->string('comment')->nullable();
            $table->timestamps();
            // 外部キー
            $table->foreign('stock_history_category_id')->references('stock_history_category_id')->on('stock_history_categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_histories');
    }
};
