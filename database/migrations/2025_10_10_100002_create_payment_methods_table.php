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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Cash, Bkash, Nagad, Bank Transfer, Cheque, Credit Card
            $table->string('code')->unique(); // cash, bkash, nagad, bank, cheque, card
            $table->text('description')->nullable();
            $table->string('account_number')->nullable();
            $table->json('gateway_config')->nullable(); // For online payment gateways
            $table->decimal('transaction_charge', 5, 2)->default(0); // Percentage
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
