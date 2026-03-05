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
        Schema::create('order_items', function (Blueprint $table){
            $table->increments('order_item_id');
            $table->string('order_control_id', 16);
            $table->boolean('is_item_allocated')->default(0);
            $table->boolean('is_stock_allocated')->default(0);
            $table->unsignedInteger('unallocated_quantity');
            // ここから受注データの内容
            $table->string('order_item_code', 255);
            $table->string('order_item_name', 255);
            $table->unsignedInteger('order_quantity');
            $table->boolean('is_auto_process_add')->default(0);
            $table->timestamps();
            // 外部キー
            $table->foreign('order_control_id')->references('order_control_id')->on('orders')->cascadeOnUpdate()->cascadeOnDelete();
        });
        // 文字セット・照合順序を変更
        DB::statement("ALTER TABLE order_items MODIFY order_item_code VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
