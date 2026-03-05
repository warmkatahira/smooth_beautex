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
        Schema::create('auto_processes', function (Blueprint $table) {
            $table->increments('auto_process_id');
            $table->string('auto_process_name', 30)->unique();
            $table->string('action_type', 30);
            $table->string('action_column_name', 30)->nullable();
            $table->string('action_value', 255)->nullable();
            $table->string('condition_match_type', 3);
            $table->boolean('is_active');
            $table->unsignedInteger('sort_order');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auto_processes');
    }
};
