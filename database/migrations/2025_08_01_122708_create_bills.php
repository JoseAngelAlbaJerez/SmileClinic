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
            $table->id();
            $table->string('type');
            $table->string('currency');
            $table->boolean('active')->default(true);
            $table->date('emission_date');
            $table->date('expiration_date')->nullable();
            $table->foreignId('doctor_id');
            $table->foreignId('patient_id');
            $table->foreignId('c_x_c_id')->nullable();
            $table->decimal('total');
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
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
