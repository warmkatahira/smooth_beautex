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
        Schema::create('order_categories', function (Blueprint $table) {
            $table->increments('order_category_id');
            $table->string('order_category_name', 10);
            $table->unsignedInteger('mall_id');
            $table->unsignedInteger('shipper_id');
            $table->unsignedInteger('sort_order');
            $table->timestamps();
            // 外部キー
            $table->foreign('mall_id')->references('mall_id')->on('malls');
            $table->foreign('shipper_id')->references('shipper_id')->on('shippers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_categories');
    }
};
