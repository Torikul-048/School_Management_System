<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->enum('notice_type', ['general', 'urgent', 'academic', 'exam', 'fee', 'holiday', 'event', 'other'])->default('general');
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->text('target_audience'); // JSON: roles, classes, sections
            $table->date('publish_date');
            $table->date('expiry_date')->nullable();
            $table->string('attachment')->nullable();
            $table->boolean('send_email')->default(false);
            $table->boolean('send_sms')->default(false);
            $table->boolean('is_pinned')->default(false);
            $table->enum('status', ['draft', 'published', 'expired', 'archived'])->default('published');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['publish_date', 'expiry_date', 'status']);
            $table->index(['notice_type', 'priority']);
            $table->index('is_pinned');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notices');
    }
};
