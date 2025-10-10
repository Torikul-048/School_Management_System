<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('complaint_number')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('subject');
            $table->text('description');
            $table->enum('category', ['academic', 'administrative', 'facility', 'transport', 'discipline', 'fee', 'teacher', 'other'])->default('other');
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('status', ['open', 'in_progress', 'resolved', 'closed', 'rejected'])->default('open');
            $table->string('attachment')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->text('admin_response')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('rating')->nullable(); // 1-5 stars
            $table->text('feedback')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'status']);
            $table->index(['category', 'priority']);
            $table->index('complaint_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
