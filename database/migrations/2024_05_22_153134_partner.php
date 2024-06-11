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
        Schema::create('partner', function (Blueprint $table) {
            $table->id();
            $table->string('erp_id')->nullable();
            $table->string('business_name');
            $table->string('vat_number')->nullable();
            $table->string('tax_id')->nullable();
            $table->string('PEC')->nullable();
            $table->string('default_address_id')->nullable();
            $table->string('default_contact_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partner');
    }
};
