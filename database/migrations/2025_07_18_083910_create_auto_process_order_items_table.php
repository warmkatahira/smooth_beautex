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
        Schema::create('auto_process_order_items', function (Blueprint $table) {
            $table->increments('auto_process_order_item_id');
            $table->unsignedInteger('auto_process_id');
            $table->unsignedInteger('order_item_id');
            $table->unsignedInteger('shipping_quantity');
            $table->timestamps();
            // 外部キー
            $table->foreign('auto_process_id')->references('auto_process_id')->on('auto_processes')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('order_item_id')->references('item_id')->on('items')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auto_process_order_items');
    }
};