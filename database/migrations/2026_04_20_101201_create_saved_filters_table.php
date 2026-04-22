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
        Schema::create('saved_filters', function (Blueprint $table) {
            $table->increments('saved_filter_id');
            $table->unsignedInteger('user_no');
            $table->string('filter_page', 30);
            $table->string('filter_name', 20);
            $table->json('filter_conditions');
            $table->timestamps();
            // 外部キー
            $table->foreign('user_no')->references('user_no')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saved_filters');
    }
};
