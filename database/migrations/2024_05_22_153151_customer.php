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
        Schema::create('customer', function (Blueprint $table) {
            $table->id();
            $table->string('erp_id')->nullable();
            $table->string('business_name');
            $table->string('vat_number')->nullable();
            $table->string('tax_id')->nullable();
            $table->string('PEC')->nullable();
            $table->string('default_address_id')->nullable();
            $table->string('default_contact_id')->nullable();
            $table->boolean('active')->nullable();
            $table->boolean('exported')->nullable();
            $table->string('price_list')->nullable();
            $table->string('product_category')->nullable();
            $table->string('sales_category')->nullable();
            $table->string('channel')->nullable();
            $table->string('seasonality')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('default_delivery_address_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer');
    }
};
