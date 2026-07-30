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
        Schema::create('item_imports', function (Blueprint $table){
            $table->increments('item_import_id');
            $table->string('item_code', 255);
            $table->string('item_jan_code', 13)->nullable();
            $table->string('item_name', 255)->nullable();
            $table->string('item_category_1', 20)->nullable();
            $table->string('item_category_2', 20)->nullable();
            $table->boolean('is_lot_managed')->default(false);
            $table->string('model_jan_code', 13)->nullable();
            $table->unsignedTinyInteger('exp_start_position')->nullable();
            $table->unsignedTinyInteger('lot_1_start_position')->nullable();
            $table->unsignedTinyInteger('lot_1_length')->nullable();
            $table->unsignedTinyInteger('lot_2_start_position')->nullable();
            $table->unsignedTinyInteger('lot_2_length')->nullable();
            $table->unsignedTinyInteger('s_power_code')->nullable();
            $table->unsignedTinyInteger('s_power_code_start_position')->nullable();
            $table->boolean('is_stock_managed')->nullable();
            $table->unsignedInteger('unit_cost')->nullable();
            $table->string('country_of_origin', 10)->nullable();
            $table->string('hs_code', 10)->nullable();
            $table->unsignedInteger('item_weight_g')->nullable();
            $table->string('brand', 50)->nullable();
            $table->string('wearing_period', 20)->nullable();
            $table->string('quantity_per_box', 20)->nullable();
            $table->string('color_id', 20)->nullable();
            $table->unsignedTinyInteger('color_row')->nullable();
            $table->string('manufacturer', 20)->nullable();
            $table->string('supplier', 20)->nullable();
            $table->string('ems_item_name', 80)->nullable();
            $table->unsignedInteger('sort_order')->nullable();
            $table->timestamps();
        });
        // 文字セット・照合順序を変更
        DB::statement("ALTER TABLE item_imports MODIFY item_code VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_imports');
    }
};
