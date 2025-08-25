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
        Schema::create('bill_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('procedure_id');
            $table->decimal('amount');
            $table->decimal('total');
            $table->string('treatment');
            $table->integer('discount');
            $table->integer('amount_of_payments')->nullable();
            $table->decimal('initial')->nullable();
            $table->integer('quantity');
            $table->foreignId('bill_id');
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
        Schema::dropIfExists('bill_details');
    }
};
