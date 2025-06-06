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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('ars');
            $table->boolean('complications');
            $table->string('complications_detail')->nullable();
            $table->boolean('alergies');
            $table->string('alergies_detail')->nullable();
            $table->boolean('drugs');
            $table->string('drugs_detail')->nullable();
            $table->string('motive');
            $table->string('address');
            $table->string('DNI');
            $table->string('phone_number');
            $table->date('date_of_birth');
            $table->boolean('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
