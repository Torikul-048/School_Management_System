<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NoticeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $notice;

    public function __construct($notice)
    {
        $this->notice = $notice;
    }

    public function via($notifiable)
    {
        $settings = \App\Models\NotificationSetting::getSettings($notifiable->id);
        $channels = ['database'];
        
        if ($settings->email_enabled && $settings->notify_notices && $this->notice->send_email) {
            $channels[] = 'mail';
        }
        
        return $channels;
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('[' . strtoupper($this->notice->priority) . '] ' . $this->notice->title)
            ->priority($this->getPriorityLevel())
            ->line($this->notice->title)
            ->line($this->notice->content)
            ->line('Notice Type: ' . ucfirst($this->notice->notice_type))
            ->action('View Notice', url('/notices/' . $this->notice->id))
            ->line('Thank you!');
    }

    public function toArray($notifiable)
    {
        return [
            'notice_id' => $this->notice->id,
            'title' => $this->notice->title,
            'content' => $this->notice->content,
            'notice_type' => $this->notice->notice_type,
            'priority' => $this->notice->priority,
            'publish_date' => $this->notice->publish_date,
        ];
    }

    private function getPriorityLevel()
    {
        return match($this->notice->priority) {
            'urgent' => 1,
            'high' => 2,
            'normal' => 3,
            'low' => 4,
            default => 3,
        };
    }
}
