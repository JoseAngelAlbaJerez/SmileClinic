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
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->boolean('active')->default(true);
            $table->date('emission_date');
            $table->date('expiration_date')->nullable();
            $table->decimal('amount_of_payments')->nullable();
            $table->decimal('initial')->nullable();
            $table->foreignId('doctor_id');
            $table->foreignId('patient_id');
            $table->decimal('total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};
