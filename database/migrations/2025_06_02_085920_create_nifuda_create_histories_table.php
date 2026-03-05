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
        Schema::create('nifuda_create_histories', function (Blueprint $table) {
            $table->increments('nifuda_create_history_id');
            $table->unsignedInteger('shipping_group_id');
            $table->unsignedInteger('shipping_method_id');
            $table->string('directory_name', 100);
            $table->unsignedInteger('created_by');
            $table->timestamps();
            // 外部キー
            $table->foreign('shipping_group_id')->references('shipping_group_id')->on('shipping_groups')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('shipping_method_id')->references('shipping_method_id')->on('shipping_methods')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nifuda_create_histories');
    }
};
