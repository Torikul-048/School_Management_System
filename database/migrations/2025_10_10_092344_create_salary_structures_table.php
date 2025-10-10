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
        Schema::create('salary_structures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->unique()->constrained()->onDelete('cascade');
            
            // Basic Salary
            $table->decimal('basic_salary', 10, 2);
            
            // Allowances
            $table->decimal('hra', 10, 2)->default(0)->comment('House Rent Allowance');
            $table->decimal('transport_allowance', 10, 2)->default(0);
            $table->decimal('medical_allowance', 10, 2)->default(0);
            $table->decimal('special_allowance', 10, 2)->default(0);
            $table->decimal('other_allowance', 10, 2)->default(0);
            $table->decimal('total_allowances', 10, 2)->default(0);
            
            // Deductions
            $table->decimal('provident_fund', 10, 2)->default(0);
            $table->decimal('professional_tax', 10, 2)->default(0);
            $table->decimal('income_tax', 10, 2)->default(0);
            $table->decimal('other_deductions', 10, 2)->default(0);
            $table->decimal('total_deductions', 10, 2)->default(0);
            
            // Totals
            $table->decimal('gross_salary', 10, 2);
            $table->decimal('net_salary', 10, 2);
            
            $table->timestamps();

            $table->index('teacher_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_structures');
    }
};
