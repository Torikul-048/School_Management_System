<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            // Add only missing columns
            if (!Schema::hasColumn('announcements', 'priority')) {
                $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->after('type')->default('normal');
            }
            if (!Schema::hasColumn('announcements', 'send_email')) {
                $table->boolean('send_email')->after('attachment')->default(false);
            }
            if (!Schema::hasColumn('announcements', 'send_sms')) {
                $table->boolean('send_sms')->after('send_email')->default(false);
            }
            if (!Schema::hasColumn('announcements', 'is_pinned')) {
                $table->boolean('is_pinned')->after('send_sms')->default(false);
            }
            if (!Schema::hasColumn('announcements', 'status')) {
                $table->enum('status', ['active', 'inactive'])->after('is_published')->default('active');
            }
        });
    }

    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $columns = ['priority', 'send_email', 'send_sms', 'is_pinned', 'status'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('announcements', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
