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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->enum('type', ['general', 'urgent', 'event', 'holiday', 'exam'])->default('general');
            $table->json('target_audience'); // ["all", "students", "teachers", "parents"]
            $table->foreignId('class_id')->nullable()->constrained()->onDelete('cascade'); // Specific class or null for all
            $table->date('publish_date');
            $table->date('expire_date')->nullable();
            $table->string('attachment')->nullable();
            $table->boolean('is_published')->default(false);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['is_published', 'publish_date']);
            $table->index(['type', 'is_published']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
