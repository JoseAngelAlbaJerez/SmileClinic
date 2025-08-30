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
            $table->string('ars')->nullable();
            $table->boolean('complications')->default(false);
            $table->string('complications_detail')->nullable();
            $table->boolean('alergies')->default(false);
            $table->string('alergies_detail')->nullable();
            $table->boolean('drugs')->default(false);
            $table->string('drugs_detail')->nullable();
            $table->string('motive')->nullable();
            $table->string('address')->nullable();
            $table->string('DNI')->nullable();
            $table->string('phone_number')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->boolean('active')->default(true);
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
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
