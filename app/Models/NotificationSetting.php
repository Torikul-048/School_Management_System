<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email_enabled',
        'sms_enabled',
        'push_enabled',
        'notify_announcements',
        'notify_events',
        'notify_notices',
        'notify_messages',
        'notify_fees',
        'notify_attendance',
        'notify_grades',
        'notify_assignments',
    ];

    protected $casts = [
        'email_enabled' => 'boolean',
        'sms_enabled' => 'boolean',
        'push_enabled' => 'boolean',
        'notify_announcements' => 'boolean',
        'notify_events' => 'boolean',
        'notify_notices' => 'boolean',
        'notify_messages' => 'boolean',
        'notify_fees' => 'boolean',
        'notify_attendance' => 'boolean',
        'notify_grades' => 'boolean',
        'notify_assignments' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getSettings($userId)
    {
        return self::firstOrCreate(
            ['user_id' => $userId],
            [
                'email_enabled' => true,
                'sms_enabled' => true,
                'push_enabled' => true,
                'notify_announcements' => true,
                'notify_events' => true,
                'notify_notices' => true,
                'notify_messages' => true,
                'notify_fees' => true,
                'notify_attendance' => true,
                'notify_grades' => true,
                'notify_assignments' => true,
            ]
        );
    }
}
