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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('expense_number')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('category', ['salary', 'utilities', 'maintenance', 'supplies', 'transportation', 'other'])->default('other');
            $table->decimal('amount', 10, 2);
            $table->date('expense_date');
            $table->enum('payment_method', ['cash', 'cheque', 'online', 'card', 'bank-transfer'])->default('cash');
            $table->string('reference_number')->nullable();
            $table->string('vendor_name')->nullable();
            $table->string('receipt')->nullable(); // File path
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('recorded_by')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['pending', 'approved', 'rejected', 'paid'])->default('pending');
            $table->timestamps();
            
            $table->index(['expense_date', 'category']);
            $table->index(['status', 'expense_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
