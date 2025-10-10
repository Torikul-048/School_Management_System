<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('phone_number');
            $table->text('message');
            $table->enum('status', ['pending', 'sent', 'failed', 'delivered'])->default('pending');
            $table->string('sms_provider')->nullable(); // twilio, nexmo, etc.
            $table->string('message_id')->nullable(); // Provider's message ID
            $table->text('response')->nullable(); // API response
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('phone_number');
            $table->index('sent_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_logs');
    }
};
