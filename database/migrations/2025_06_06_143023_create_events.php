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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('doctor_id');
            $table->foreignId('patient_id');
            $table->string('google_event_id')->nullable();
            $table->boolean('attended');
            $table->date('date');
            $table->time('starttime');
            $table->time('endtime');
            $table->boolean('active');
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
