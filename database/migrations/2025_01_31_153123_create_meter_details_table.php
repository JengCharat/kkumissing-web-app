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
        Schema::create('meter_details', function (Blueprint $table) {
            $table->id('meter_detailID');
            $table->decimal('water_meter_start', 10, 2)->nullable();
            $table->decimal('water_meter_end', 10, 2)->nullable();
            $table->decimal('electricity_meter_start', 10, 2)->nullable();
            $table->decimal('electricity_meter_end', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meter_details');
    }
};
