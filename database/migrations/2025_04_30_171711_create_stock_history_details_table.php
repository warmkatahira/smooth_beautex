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
        Schema::create('stock_history_details', function (Blueprint $table){
            $table->increments('stock_history_detail_id');
            $table->unsignedInteger('stock_history_id');
            $table->unsignedInteger('stock_id');
            $table->integer('quantity');
            $table->timestamps();
            // 外部キー
            $table->foreign('stock_history_id')->references('stock_history_id')->on('stock_histories')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_history_details');
    }
};
