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

        Schema::create('bills', function (Blueprint $table) {
            $table->id('billID');
            $table->foreignId('roomID')->nullable()->constrained('rooms', 'roomID');
            $table->foreignId('tenant_id')->nullable()->constrained('tenants', 'tenantID');
            $table->decimal('total_price',10,2)->nullable();
            $table->string('status')->default('unpaid');
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
