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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('admission_number')->unique();
            $table->date('admission_date');
            $table->foreignId('class_id')->constrained()->onDelete('cascade');
            $table->foreignId('section_id')->constrained()->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained()->onDelete('cascade');
            $table->integer('roll_number')->nullable();
            
            // Personal Information
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->enum('blood_group', ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'])->nullable();
            $table->string('religion')->nullable();
            $table->string('nationality')->default('USA');
            $table->text('current_address')->nullable();
            $table->text('permanent_address')->nullable();
            
            // Guardian Information
            $table->string('father_name');
            $table->string('father_phone')->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('mother_name');
            $table->string('mother_phone')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->foreignId('parent_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('guardian_name')->nullable();
            $table->string('guardian_phone')->nullable();
            $table->string('guardian_relation')->nullable();
            
            // Documents
            $table->string('birth_certificate')->nullable();
            $table->string('transfer_certificate')->nullable();
            $table->json('documents')->nullable(); // Additional documents
            
            // Medical Information
            $table->text('medical_history')->nullable();
            $table->text('allergies')->nullable();
            
            $table->enum('status', ['active', 'inactive', 'graduated', 'transferred'])->default('active');
            $table->timestamps();
            
            $table->index(['class_id', 'section_id', 'status']);
            $table->index('admission_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
