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
            $table->string('status')->default('Available'); // e.g., 'available', 'not available'
            $table->decimal('daily_rate', 10, 2)->nullable();
            $table->decimal('month_rate', 10, 2)->default(4000.00);
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
