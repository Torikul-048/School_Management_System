<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'avatar',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the student record associated with the user
     */
    public function student()
    {
        return $this->hasOne(Student::class, 'user_id');
    }

    /**
     * Get the teacher record associated with the user
     */
    public function teacher()
    {
        return $this->hasOne(Teacher::class, 'user_id');
    }

    /**
     * Get sent messages
     */
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Get received messages
     */
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    /**
     * Get user's complaints
     */
    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    /**
     * Get notification settings
     */
    public function notificationSettings()
    {
        return $this->hasOne(NotificationSetting::class);
    }

    /**
     * Get SMS logs
     */
    public function smsLogs()
    {
        return $this->hasMany(SmsLog::class);
    }
}
