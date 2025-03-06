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
        Schema::create('queues', function (Blueprint $table) {
            $table->id();
            $table->string('roomNumber');
            $table->string('tenantName');
            $table->string('tenant_type')->nullable();
            $table->string('telNumber')->nullable();
            $table->date('contract_date')->nullable();
            $table->string('contract_file')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('booking_type')->nullable(); // 'daily', 'monthly'
            $table->date('check_in')->nullable();
            $table->date('check_out')->nullable();
            $table->decimal('deposit', 10, 2)->nullable();
            $table->decimal('damage_fee', 10, 2)->nullable();
            $table->decimal('overdue_fee', 10, 2)->nullable();
            $table->date('due_date')->nullable();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queues');
    }
};
