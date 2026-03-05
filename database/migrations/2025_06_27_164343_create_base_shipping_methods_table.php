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
        Schema::create('base_shipping_methods', function (Blueprint $table) {
            $table->increments('base_shipping_method_id');
            $table->string('base_id', 10);
            $table->unsignedInteger('shipping_method_id');
            $table->string('setting_1', 20)->nullable();
            $table->string('setting_2', 20)->nullable();
            $table->string('setting_3', 20)->nullable();
            $table->unsignedInteger('e_hiden_version_id')->nullable();
            $table->timestamps();
            // 外部キー
            $table->foreign('base_id')->references('base_id')->on('bases');
            $table->foreign('shipping_method_id')->references('shipping_method_id')->on('shipping_methods');
            $table->foreign('e_hiden_version_id')->references('e_hiden_version_id')->on('e_hiden_versions');
            // 複合ユニーク制約を追加
            $table->unique(['base_id', 'shipping_method_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('base_shipping_methods');
    }
};
