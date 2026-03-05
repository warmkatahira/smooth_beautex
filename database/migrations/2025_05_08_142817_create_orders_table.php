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
        Schema::create('orders', function (Blueprint $table){
            $table->bigIncrements('order_id');
            $table->string('order_control_id', 16)->unique();
            $table->date('order_import_date');
            $table->time('order_import_time');
            $table->unsignedInteger('order_status_id');
            $table->unsignedInteger('shipping_method_id');
            $table->string('shipping_base_id', 10)->nullable();
            $table->unsignedInteger('shipper_id');
            $table->date('desired_delivery_date')->nullable();
            $table->string('desired_delivery_time', 20)->nullable();
            $table->boolean('is_allocated')->default(0);
            $table->boolean('is_shipping_inspection_complete')->default(0);
            $table->timestamp('shipping_inspection_date')->nullable();
            $table->string('tracking_no', 14)->nullable();
            $table->date('shipping_date')->nullable();
            $table->unsignedInteger('shipping_group_id')->nullable();
            // ここから受注データの内容
            $table->string('order_no', 50);
            $table->date('order_date');
            $table->time('order_time');
            $table->string('ship_name', 255);
            $table->string('ship_zip_code', 8);
            $table->string('ship_prefecture_name', 5);
            $table->string('ship_address');
            $table->string('ship_tel', 15);
            // ここまで受注データの内容
            $table->text('order_memo')->nullable();
            $table->text('shipping_work_memo')->nullable();
            $table->unsignedInteger('order_category_id');
            $table->string('order_mark', 10)->nullable();
            $table->timestamps();
            // 外部キー
            $table->foreign('shipping_group_id')->references('shipping_group_id')->on('shipping_groups')->cascadeOnUpdate()->onDelete('set null');
            $table->foreign('shipping_method_id')->references('shipping_method_id')->on('shipping_methods')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('shipping_base_id')->references('base_id')->on('bases')->cascadeOnUpdate();
            $table->foreign('shipper_id')->references('shipper_id')->on('shippers')->cascadeOnUpdate();
            $table->foreign('order_category_id')->references('order_category_id')->on('order_categories')->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
