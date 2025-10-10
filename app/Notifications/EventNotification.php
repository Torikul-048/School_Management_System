<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $event;
    public $type; // 'created', 'updated', 'cancelled', 'reminder'

    public function __construct($event, $type = 'created')
    {
        $this->event = $event;
        $this->type = $type;
    }

    public function via($notifiable)
    {
        $settings = \App\Models\NotificationSetting::getSettings($notifiable->id);
        $channels = ['database'];
        
        if ($settings->email_enabled && $settings->notify_events && $this->event->send_notification) {
            $channels[] = 'mail';
        }
        
        return $channels;
    }

    public function toMail($notifiable)
    {
        $subject = match($this->type) {
            'created' => 'New Event: ' . $this->event->title,
            'updated' => 'Event Updated: ' . $this->event->title,
            'cancelled' => 'Event Cancelled: ' . $this->event->title,
            'reminder' => 'Event Reminder: ' . $this->event->title,
            default => 'Event Notification: ' . $this->event->title,
        };

        return (new MailMessage)
            ->subject($subject)
            ->line($this->event->title)
            ->line($this->event->description)
            ->line('Event Type: ' . ucfirst($this->event->event_type))
            ->line('Date: ' . $this->event->start_date->format('d M, Y') . ' to ' . $this->event->end_date->format('d M, Y'))
            ->line('Location: ' . ($this->event->location ?? 'TBA'))
            ->action('View Event Details', url('/events/' . $this->event->id))
            ->line('Thank you!');
    }

    public function toArray($notifiable)
    {
        return [
            'event_id' => $this->event->id,
            'title' => $this->event->title,
            'description' => $this->event->description,
            'event_type' => $this->event->event_type,
            'start_date' => $this->event->start_date,
            'end_date' => $this->event->end_date,
            'location' => $this->event->location,
            'type' => $this->type,
        ];
    }
}
