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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->string('month'); // Format: Y-m (e.g., 2025-01)
            $table->date('payment_date');
            $table->decimal('basic_salary', 10, 2);
            $table->decimal('allowances', 10, 2)->default(0);
            $table->decimal('deductions', 10, 2)->default(0);
            $table->decimal('gross_salary', 10, 2);
            $table->decimal('net_salary', 10, 2);
            $table->enum('payment_method', ['cash', 'bank_transfer', 'cheque'])->default('bank_transfer');
            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending');
            $table->text('remarks')->nullable();
            $table->integer('working_days')->nullable();
            $table->integer('present_days')->nullable();
            $table->integer('absent_days')->nullable();
            $table->decimal('attendance_deduction', 10, 2)->default(0);
            $table->timestamps();

            $table->index(['teacher_id', 'month']);
            $table->index('status');
            $table->index('payment_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
