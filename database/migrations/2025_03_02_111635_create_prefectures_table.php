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
        Schema::create('prefectures', function (Blueprint $table){
            $table->increments('prefecture_id');
            $table->string('prefecture_name', 5);
            $table->string('shipping_base_id', 10)->nullable();
            $table->timestamps();
            // 外部キー
            $table->foreign('shipping_base_id')->references('base_id')->on('bases')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prefectures');
    }
};
