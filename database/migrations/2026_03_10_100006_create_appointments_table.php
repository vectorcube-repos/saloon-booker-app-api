<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('salon_id')->constrained('salons')->cascadeOnDelete();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->foreignId('provider_id')->nullable()->constrained('service_providers')->nullOnDelete();
            $table->timestampTz('slot_start');
            $table->timestampTz('slot_end');
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('confirmed');
            $table->text('notes')->nullable();
            $table->timestampsTz();

            $table->index(['salon_id', 'slot_start']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
