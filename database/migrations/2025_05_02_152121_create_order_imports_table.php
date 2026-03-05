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
        Schema::create('order_imports', function (Blueprint $table){
            $table->increments('order_import_id');
            $table->string('order_control_id', 16)->nullable();
            $table->date('order_import_date');
            $table->time('order_import_time');
            $table->unsignedInteger('order_status_id');
            $table->unsignedInteger('shipping_method_id');
            $table->string('shipping_base_id', 10)->nullable();
            $table->string('shipper_id', 20)->nullable();
            $table->date('desired_delivery_date')->nullable();
            $table->string('desired_delivery_time', 20)->nullable();
            // ここから受注データの内容
            $table->string('order_no', 50);
            $table->date('order_date');
            $table->time('order_time');
            $table->string('ship_name', 255);
            $table->string('ship_zip_code', 8);
            $table->string('ship_prefecture_name', 5);
            $table->string('ship_address', 255);
            $table->string('ship_tel', 15);
            $table->string('order_item_code', 255);
            $table->string('order_item_name', 255);
            $table->unsignedInteger('order_quantity');
            $table->string('seller_item_code', 50);
            // ここまで受注データの内容
            $table->unsignedInteger('unallocated_quantity');
            $table->unsignedInteger('order_category_id');
            $table->timestamps();
        });
        // 文字セット・照合順序を変更
        DB::statement("ALTER TABLE order_imports MODIFY order_item_code VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_imports');
    }
};
