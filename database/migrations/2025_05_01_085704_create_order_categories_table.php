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
            $table->string('order_category_image_file_name', 50)->default('no_image.png');
            $table->unsignedInteger('shipper_id');
            $table->unsignedInteger('sort_order');
            $table->timestamps();
            // 外部キー
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
