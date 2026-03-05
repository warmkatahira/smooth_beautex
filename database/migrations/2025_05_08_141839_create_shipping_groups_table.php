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
        Schema::create('shipping_groups', function (Blueprint $table){
            $table->increments('shipping_group_id');
            $table->string('shipping_group_name', 20);
            $table->string('shipping_base_id', 10);
            $table->date('estimated_shipping_date');
            $table->timestamps();
            // 外部キー
            $table->foreign('shipping_base_id')->references('base_id')->on('bases')->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_groups');
    }
};
