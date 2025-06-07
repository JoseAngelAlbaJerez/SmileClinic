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
        Schema::create('odontographs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->json('cavities')->nullable();
            $table->json('extraction')->nullable();
            $table->json('crown')->nullable();
            $table->boolean('filter')->default(false);
            $table->json('fracture')->nullable();
            $table->boolean('missing')->nullable();
            $table->json('restoration')->nullable();
            $table->boolean('root_canal')->nullable();
            $table->json('braces')->nullable();
            $table->json('implant')->nullable();
            $table->json('bridge')->nullable();
            $table->json('dentures')->nullable();
            $table->boolean('cleaning')->nullable();
            $table->date('veener')->nullable();
            $table->date('graft')->nullable();
            $table->date('sealant')->nullable();
            $table->date('bonding')->nullable();
            $table->date('examinations')->nullable();
            $table->date('fissure_seal')->nullable();
            $table->boolean('active')->default(true);
            $table->unsignedBigInteger('doctor_id')->nullable();
            $table->foreign('doctor_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('odontographs');
    }
};
