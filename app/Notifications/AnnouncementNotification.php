<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AnnouncementNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $announcement;

    public function __construct($announcement)
    {
        $this->announcement = $announcement;
    }

    public function via($notifiable)
    {
        $settings = \App\Models\NotificationSetting::getSettings($notifiable->id);
        $channels = ['database'];
        
        if ($settings->email_enabled && $settings->notify_announcements && $this->announcement->send_email) {
            $channels[] = 'mail';
        }
        
        return $channels;
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Announcement: ' . $this->announcement->title)
            ->priority($this->getPriorityLevel())
            ->line($this->announcement->title)
            ->line($this->announcement->content)
            ->action('View Announcement', url('/announcements/' . $this->announcement->id))
            ->line('Thank you for using our School Management System!');
    }

    public function toArray($notifiable)
    {
        return [
            'announcement_id' => $this->announcement->id,
            'title' => $this->announcement->title,
            'content' => $this->announcement->content,
            'priority' => $this->announcement->priority,
            'created_at' => $this->announcement->created_at,
        ];
    }

    private function getPriorityLevel()
    {
        return match($this->announcement->priority) {
            'urgent' => 1,
            'high' => 2,
            'normal' => 3,
            'low' => 4,
            default => 3,
        };
    }
}
