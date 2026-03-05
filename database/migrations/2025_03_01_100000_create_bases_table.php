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
        Schema::create('bases', function (Blueprint $table){
            $table->string('base_id', 10)->primary();
            $table->string('base_name', 20)->unique();
            $table->string('base_color_code', 7)->default('#ffffff');
            $table->string('mieru_customer_code', 20);
            $table->unsignedInteger('sort_order')->default(100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bases');
    }
};
