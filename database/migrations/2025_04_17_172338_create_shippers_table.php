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
        Schema::create('shippers', function (Blueprint $table){
            $table->increments('shipper_id');
            $table->string('shipper_company_name', 50);
            $table->string('shipper_name', 50);
            $table->string('shipper_zip_code', 8);
            $table->string('shipper_address', 255);
            $table->string('shipper_tel', 13);
            $table->string('shipper_email', 100)->nullable();
            $table->string('shipper_invoice_no', 30)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shippers');
    }
};
