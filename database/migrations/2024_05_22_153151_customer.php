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
            $table->boolean('active');
            $table->boolean('exported');
            $table->string('price_list');
            $table->string('product_category');
            $table->string('sales_category');
            $table->string('channel');
            $table->string('seasonality');
            $table->string('payment_method');
            $table->string('partner_id');
            $table->string('default_delivery_address_id');
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
