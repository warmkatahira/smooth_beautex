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
        Schema::create('order_item_lots', function (Blueprint $table){
            $table->increments('order_item_lot_id');
            $table->unsignedInteger('order_item_id');
            $table->string('lot', 20);
            $table->unsignedInteger('quantity');
            $table->timestamps();
            // 外部キー
            $table->foreign('order_item_id')->references('order_item_id')->on('order_items')->cascadeOnUpdate()->cascadeOnDelete();
            // 複合ユニーク制約を追加
            $table->unique(['order_item_id', 'lot']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_item_lots');
    }
};
