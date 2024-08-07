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
        Schema::create('agent_zone', function (Blueprint $table) {
            $table->id();
            $table->string('zone_id')->nullable();
            $table->string('agent_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['zone_id', 'agent_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_zone');
    }
};
