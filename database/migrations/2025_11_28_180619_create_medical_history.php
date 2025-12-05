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
        Schema::create('medical_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            $table->boolean('complications')->default(false);
            $table->string('complications_detail')->nullable();
            $table->boolean('alergies')->default(false);
            $table->string('alergies_detail')->nullable();
            $table->boolean('drugs')->default(false);
            $table->string('drugs_detail')->nullable();
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->boolean('active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_histories');
    }
};
