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
        Schema::create('e_hiden_versions', function (Blueprint $table) {
            $table->increments('e_hiden_version_id');
            $table->string('e_hiden_version', 10);
            $table->string('file_name', 20);
            $table->string('file_extension', 5);
            $table->unsignedInteger('data_start_row');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('e_hiden_versions');
    }
};
