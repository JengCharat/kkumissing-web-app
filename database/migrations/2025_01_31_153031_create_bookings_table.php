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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id('bookingID');
            $table->foreignId('tenant_id')->constrained('tenants', 'tenantID'); // Foreign key to tenants table
            $table->foreignId('room_id')->nullable()->constrained('rooms', 'roomID'); // Foreign key to rooms table
            $table->string('booking_type')->nullable(); // 'daily', 'monthly'
            $table->date('check_in')->nullable();
            $table->date('check_out')->nullable();
            $table->decimal('deposit', 10, 2)->nullable();
            $table->date('due_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
