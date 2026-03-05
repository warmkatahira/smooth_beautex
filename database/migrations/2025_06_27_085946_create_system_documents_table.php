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
        Schema::create('system_documents', function (Blueprint $table) {
            $table->increments('system_document_id');
            $table->string('file_name');
            $table->unsignedInteger('sort_order');
            $table->boolean('is_internal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_documents');
    }
};
