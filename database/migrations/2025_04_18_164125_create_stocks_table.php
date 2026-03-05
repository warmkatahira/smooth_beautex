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
        Schema::create('stocks', function (Blueprint $table){
            $table->increments('stock_id');
            $table->string('base_id', 10);
            $table->unsignedInteger('item_id');
            $table->unsignedInteger('total_stock')->default(0);
            $table->unsignedInteger('available_stock')->default(0);
            $table->string('item_location', 20)->nullable();
            $table->timestamps();
            // 外部キー
            $table->foreign('base_id')->references('base_id')->on('bases')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('item_id')->references('item_id')->on('items')->cascadeOnUpdate()->restrictOnDelete();
            // 複合ユニーク制約を追加
            $table->unique(['base_id', 'item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
