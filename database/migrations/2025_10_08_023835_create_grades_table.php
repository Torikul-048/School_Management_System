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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('exam_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('class_id')->constrained()->onDelete('cascade');
            $table->foreignId('section_id')->constrained()->onDelete('cascade');
            $table->decimal('theory_marks', 5, 2)->nullable();
            $table->decimal('practical_marks', 5, 2)->nullable();
            $table->decimal('total_marks', 5, 2)->nullable();
            $table->decimal('obtained_marks', 5, 2);
            $table->string('grade')->nullable(); // A+, A, B, C, etc.
            $table->decimal('percentage', 5, 2)->nullable();
            $table->enum('result', ['pass', 'fail', 'absent'])->default('pass');
            $table->text('remarks')->nullable();
            $table->foreignId('entered_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['student_id', 'exam_id', 'subject_id'], 'student_exam_subject_unique');
            $table->index(['exam_id', 'class_id', 'section_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
