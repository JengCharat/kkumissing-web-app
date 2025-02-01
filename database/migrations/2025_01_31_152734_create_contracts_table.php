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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id('contractID');
            $table->foreignId('room_id')->constrained('rooms', 'roomID'); // Foreign key to rooms table
            $table->foreignId('tenant_id')->constrained('tenants', 'tenantID'); // Foreign key to tenants table
            $table->date('contract_date')->nullable();
            $table->string('contract_file')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
