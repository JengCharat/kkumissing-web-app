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
        Schema::create('tenants', function (Blueprint $table) {
            $table->id('tenantID');
            $table->foreignId('user_id_tenant')->constrained('users', 'id'); // Foreign key to rooms table
            $table->string('tenantName');
            $table->string('tenant_type')->nullable(); // e.g., 'individual', 'company'
            $table->string('telNumber')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
