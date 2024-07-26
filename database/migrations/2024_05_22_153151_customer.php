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
            $table->string('erp_id')->nullable()->unique();
            $table->string('business_name')->nullable();
            $table->string('vat_number')->nullable();
            $table->string('PEC')->nullable();
            $table->string('default_address')->nullable();
            $table->string('default_contact')->nullable();
            $table->boolean('exported')->nullable();
            $table->string('price_list')->nullable();
            $table->string('product_category')->nullable();
            $table->string('sales_category')->nullable();
            $table->string('channel')->nullable();
            $table->string('seasonality')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('default_delivery_address')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
