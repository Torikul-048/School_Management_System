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
        Schema::create('fee_payments', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_number')->unique();
            $table->foreignId('fee_invoice_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->decimal('amount_paid', 10, 2);
            $table->date('payment_date');
            $table->enum('payment_method', ['cash', 'cheque', 'online', 'card', 'bank-transfer'])->default('cash');
            $table->string('transaction_id')->nullable();
            $table->string('cheque_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('received_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['student_id', 'payment_date']);
            $table->index('fee_invoice_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_payments');
    }
};
