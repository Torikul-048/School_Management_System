<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function via($notifiable)
    {
        $settings = \App\Models\NotificationSetting::getSettings($notifiable->id);
        $channels = ['database'];
        
        if ($settings->email_enabled && $settings->notify_messages) {
            $channels[] = 'mail';
        }
        
        return $channels;
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Message: ' . $this->message->subject)
            ->priority($this->getPriorityLevel())
            ->line('You have received a new message from ' . $this->message->sender->name)
            ->line('Subject: ' . $this->message->subject)
            ->line($this->message->message)
            ->action('View Message', url('/messages/' . $this->message->id))
            ->line('Thank you!');
    }

    public function toArray($notifiable)
    {
        return [
            'message_id' => $this->message->id,
            'sender_id' => $this->message->sender_id,
            'sender_name' => $this->message->sender->name,
            'subject' => $this->message->subject,
            'message' => $this->message->message,
            'priority' => $this->message->priority,
            'created_at' => $this->message->created_at,
        ];
    }

    private function getPriorityLevel()
    {
        return match($this->message->priority) {
            'urgent' => 1,
            'high' => 2,
            'normal' => 3,
            'low' => 4,
            default => 3,
        };
    }
}
