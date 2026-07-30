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
            $table->string('item_jan_code', 13)->unique();
            $table->string('item_name', 255);
            $table->string('item_category_1', 20)->nullable();
            $table->string('item_category_2', 20)->nullable();
            $table->boolean('is_lot_managed')->default(true);
            $table->string('model_jan_code', 13)->nullable();
            $table->unsignedTinyInteger('exp_start_position')->default(99)->nullable();
            $table->unsignedTinyInteger('lot_1_start_position')->default(99)->nullable();
            $table->unsignedTinyInteger('lot_1_length')->default(99)->nullable();
            $table->unsignedTinyInteger('lot_2_start_position')->nullable();
            $table->unsignedTinyInteger('lot_2_length')->nullable();
            $table->unsignedTinyInteger('s_power_code')->nullable();
            $table->unsignedTinyInteger('s_power_code_start_position')->nullable();
            $table->boolean('is_stock_managed')->default(true);
            $table->string('item_image_file_name', 50)->default('no_image.png');
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
            $table->unsignedInteger('sort_order')->default(99999);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
