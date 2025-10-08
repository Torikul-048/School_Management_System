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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->morphs('attendable'); // For polymorphic relation (student or teacher)
            $table->date('date');
            $table->enum('status', ['present', 'absent', 'late', 'half-day', 'on-leave'])->default('present');
            $table->foreignId('class_id')->nullable()->constrained()->onDelete('cascade'); // For students
            $table->foreignId('section_id')->nullable()->constrained()->onDelete('cascade'); // For students
            $table->foreignId('subject_id')->nullable()->constrained()->onDelete('set null'); // If attendance per subject
            $table->foreignId('marked_by')->constrained('users')->onDelete('cascade'); // Who marked the attendance
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
            
            $table->unique(['attendable_type', 'attendable_id', 'date', 'subject_id'], 'attendance_unique');
            $table->index(['date', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
