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
        Schema::create('Payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cxc_id');
            $table->foreignId('amount_paid');
            $table->foreignId('remaining_amount');
            $table->foreignId('budget_detail_id');
            $table->decimal('total');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Payments');
    }
};
