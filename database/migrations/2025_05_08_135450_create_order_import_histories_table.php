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
        Schema::create('order_import_histories', function (Blueprint $table){
            $table->increments('order_import_history_id');
            $table->string('import_file_name')->nullable();
            $table->unsignedInteger('all_order_num')->nullable();
            $table->unsignedInteger('import_order_num')->nullable();
            $table->unsignedInteger('delete_order_num')->nullable();
            $table->string('error_file_name')->nullable();
            $table->string('message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_import_histories');
    }
};
