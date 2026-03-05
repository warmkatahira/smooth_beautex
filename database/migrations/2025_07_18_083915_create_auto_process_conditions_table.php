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
        Schema::create('auto_process_conditions', function (Blueprint $table) {
            $table->increments('auto_process_condition_id');
            $table->unsignedInteger('auto_process_id');
            $table->string('column_name', 30);
            $table->string('operator', 15);
            $table->string('value', 255)->nullable();
            $table->timestamps();
            // 外部キー
            $table->foreign('auto_process_id')->references('auto_process_id')->on('auto_processes')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auto_process_conditions');
    }
};
