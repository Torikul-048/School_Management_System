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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('employee_id')->unique();
            $table->date('joining_date');
            
            // Professional Information
            $table->string('designation'); // e.g., "Senior Teacher", "Assistant Professor"
            $table->string('qualification'); // e.g., "M.Ed", "Ph.D"
            $table->text('specialization')->nullable();
            $table->integer('experience_years')->default(0);
            
            // Personal Information
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->enum('blood_group', ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'])->nullable();
            $table->string('nationality')->default('USA');
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable();
            
            // Contact & Address
            $table->string('emergency_contact')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->text('current_address')->nullable();
            $table->text('permanent_address')->nullable();
            
            // Employment Details
            $table->decimal('salary', 10, 2)->nullable();
            $table->enum('employment_type', ['full-time', 'part-time', 'contract'])->default('full-time');
            $table->string('bank_name')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('tax_id')->nullable();
            
            // Documents
            $table->string('resume')->nullable();
            $table->json('certificates')->nullable();
            $table->json('documents')->nullable();
            
            $table->enum('status', ['active', 'inactive', 'resigned', 'terminated'])->default('active');
            $table->timestamps();
            
            $table->index('employee_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
