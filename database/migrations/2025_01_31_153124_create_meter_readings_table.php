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
            $table->foreignId('tenant_id')->nullable()->constrained('tenants', 'tenantID');
            $table->foreignId('meter_details_id')->constrained('meter_details', 'meter_detailID'); // Foreign key to meter_details table
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
