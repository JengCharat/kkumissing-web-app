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
        Schema::create('bills', function (Blueprint $table) {
            $table->id('BillNo');
            $table->foreignId('roomID')->constrained('rooms', 'roomID');
            $table->foreignId('tenantID')->constrained('tenants', 'tenantID');
            $table->date('BillDate');
            $table->decimal('room_rate', 10, 2)->nullable();
            $table->decimal('damage_fee', 10, 2)->nullable();
            $table->decimal('overdue_fee', 10, 2)->nullable();
            $table->decimal('water_price', 10, 2)->nullable();
            $table->decimal('electricity_price', 10, 2)->nullable();
            $table->decimal('total_price', 10, 2)->nullable();
            $table->string('status')->default('รอชำระเงิน');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
