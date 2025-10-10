<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->boolean('email_enabled')->default(true);
            $table->boolean('sms_enabled')->default(true);
            $table->boolean('push_enabled')->default(true);
            $table->boolean('notify_announcements')->default(true);
            $table->boolean('notify_events')->default(true);
            $table->boolean('notify_notices')->default(true);
            $table->boolean('notify_messages')->default(true);
            $table->boolean('notify_fees')->default(true);
            $table->boolean('notify_attendance')->default(true);
            $table->boolean('notify_grades')->default(true);
            $table->boolean('notify_assignments')->default(true);
            $table->timestamps();

            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_settings');
    }
};
