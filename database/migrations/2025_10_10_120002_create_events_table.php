<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('event_type', ['academic', 'sports', 'cultural', 'holiday', 'exam', 'meeting', 'other'])->default('other');
            $table->date('start_date');
            $table->date('end_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('location')->nullable();
            $table->string('organizer')->nullable();
            $table->text('target_audience')->nullable(); // JSON: roles, classes, sections
            $table->string('image')->nullable();
            $table->boolean('is_public')->default(true);
            $table->boolean('send_notification')->default(false);
            $table->enum('status', ['draft', 'published', 'cancelled', 'completed'])->default('published');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['start_date', 'end_date', 'status']);
            $table->index('event_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
