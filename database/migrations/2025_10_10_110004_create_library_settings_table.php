<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('library_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('max_books_per_student')->default(3);
            $table->integer('max_books_per_teacher')->default(5);
            $table->integer('student_issue_days')->default(14);
            $table->integer('teacher_issue_days')->default(30);
            $table->decimal('fine_per_day', 10, 2)->default(5.00);
            $table->integer('max_renewal_times')->default(2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('library_settings');
    }
};
