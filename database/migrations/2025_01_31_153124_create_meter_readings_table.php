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
        Schema::create('meter_readings', function (Blueprint $table) {
            $table->id('meterID');
            $table->foreignId('room_id')->constrained('rooms', 'roomID'); // Foreign key to rooms table
            $table->foreignId('tenant_id')->constrained('tenants', 'tenantID'); // Foreign key to tenants table
            $table->date('reading_date')->nullable();
            $table->decimal('water_meter', 10, 2)->nullable();
            $table->decimal('electricity_meter', 10, 2)->nullable();
            $table->date('start_date')->nullable(); // refers to start reading date
            $table->date('end_date')->nullable();   // refers to end reading date
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meter_readings');
    }
};
