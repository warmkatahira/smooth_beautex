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
        Schema::create('item_qr_analysis_histories', function (Blueprint $table) {
            $table->increments('item_qr_analysis_history_id');
            $table->string('qr_code', 50);
            $table->string('jan_code', 13);
            $table->string('lot', 20);
            $table->boolean('is_jan_code_match')->nullable();
            $table->string('power', 10)->nullable();
            $table->unsignedTinyInteger('s_power_code')->nullable();
            $table->unsignedTinyInteger('s_power_code_start_position')->nullable();
            $table->boolean('is_lot_match')->nullable();
            $table->unsignedTinyInteger('lot_start_position')->nullable();
            $table->unsignedTinyInteger('lot_length')->nullable();
            $table->unsignedTinyInteger('exp_start_position')->nullable();
            $table->string('exp', 6)->nullable();
            $table->unsignedInteger('user_no');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_qr_analysis_histories');
    }
};
