<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('category', ['academic', 'financial', 'attendance', 'student', 'teacher', 'library', 'general'])->default('general');
            $table->text('description')->nullable();
            $table->json('parameters')->nullable(); // JSON array of required parameters
            $table->json('columns')->nullable(); // JSON array of columns to display
            $table->text('query')->nullable(); // SQL query template
            $table->string('controller_method')->nullable(); // Alternative: Controller@method
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->index(['category', 'is_active']);
            $table->index('slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_templates');
    }
};
