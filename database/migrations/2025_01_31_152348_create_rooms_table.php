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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id('roomID');
            $table->string('roomNumber');
            $table->string('status')->default('available'); // e.g., 'available', 'occupied', 'cleaning'
            $table->decimal('daily_rate', 10, 2)->nullable();
            $table->decimal('water_price', 10, 2)->nullable();
            $table->decimal('electricity_price', 10, 2)->nullable();
            $table->decimal('overdue_fee_rate', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
