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
        // Schema::create('bills', function (Blueprint $table) {
        //     $table->id();
        //     $table->timestamps();

        Schema::create('meter_readings', function (Blueprint $table) {
            $table->id('billID');
            $table->foreignId('rent')->constrained('rooms', 'daily_rate'); // Foreign key to rooms table
            $table->foreignId('water_price')->constrained('rooms', 'water_price'); // Foreign key to rooms table
            $table->foreignId('electricity_price')->constrained('rooms', 'electricity_price'); // Foreign key to rooms table
            $table->foreignId('tenant_id')->nullable()->constrained('tenants', 'tenantID');
            $table->decimal('total_price',10,2)->nullable();
            $table->timestamps();
        });
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
