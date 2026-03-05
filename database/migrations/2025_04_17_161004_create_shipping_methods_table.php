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
        Schema::create('shipping_methods', function (Blueprint $table){
            $table->increments('shipping_method_id');
            $table->string('shipping_method', 10);
            $table->unsignedInteger('delivery_company_id');
            $table->timestamps();
            // 外部キー
            $table->foreign('delivery_company_id')->references('delivery_company_id')->on('delivery_companies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_methods');
    }
};
