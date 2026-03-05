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
        Schema::create('item_qr_analysis_histroys', function (Blueprint $table) {
            $table->increments('item_qr_analysis_history_id');
            $table->string('doari_qr', 50)->nullable();
            $table->string('doari_jan', 20)->nullable();
            $table->string('doari_lot', 20)->nullable();
            $table->string('doari_power', 10)->nullable();
            $table->string('item_type', 10)->nullable();
            $table->unsignedInteger('lot_start_position')->nullable();
            $table->unsignedInteger('s_power_code')->nullable();
            $table->unsignedInteger('s_power_code_start_position')->nullable();
            $table->string('message', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_qr_analysis_histroys');
    }
};
