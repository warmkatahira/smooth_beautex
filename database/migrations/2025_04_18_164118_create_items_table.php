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
        Schema::create('items', function (Blueprint $table){
            $table->increments('item_id');
            $table->string('item_code', 255)->unique();
            $table->string('item_jan_code', 13);
            $table->string('item_name', 255);
            $table->string('item_category', 20)->nullable();
            $table->string('model_jan_code', 13)->nullable();
            $table->unsignedInteger('exp_start_position')->nullable();
            $table->unsignedInteger('lot_1_start_position')->nullable();
            $table->unsignedInteger('lot_1_length')->nullable();
            $table->unsignedInteger('lot_2_start_position')->nullable();
            $table->unsignedInteger('lot_2_length')->nullable();
            $table->unsignedInteger('s_power_code')->nullable();
            $table->unsignedInteger('s_power_code_start_position')->nullable();
            $table->boolean('is_stock_managed');
            $table->string('item_image_file_name', 50)->default('no_image.png');
            $table->unsignedInteger('sort_order')->nullable();
            $table->timestamps();
        });
        // 文字セット・照合順序を変更
        DB::statement("ALTER TABLE items MODIFY item_code VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin UNIQUE");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
