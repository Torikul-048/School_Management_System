<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ComplaintNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $complaint;
    public $type; // 'created', 'assigned', 'updated', 'resolved'

    public function __construct($complaint, $type = 'created')
    {
        $this->complaint = $complaint;
        $this->type = $type;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $subject = match($this->type) {
            'created' => 'Complaint Registered: ' . $this->complaint->complaint_number,
            'assigned' => 'Complaint Assigned to You: ' . $this->complaint->complaint_number,
            'updated' => 'Complaint Status Updated: ' . $this->complaint->complaint_number,
            'resolved' => 'Complaint Resolved: ' . $this->complaint->complaint_number,
            default => 'Complaint Notification: ' . $this->complaint->complaint_number,
        };

        $mail = (new MailMessage)
            ->subject($subject)
            ->line('Complaint Number: ' . $this->complaint->complaint_number)
            ->line('Subject: ' . $this->complaint->subject)
            ->line('Category: ' . ucfirst($this->complaint->category))
            ->line('Priority: ' . ucfirst($this->complaint->priority))
            ->line('Status: ' . ucfirst(str_replace('_', ' ', $this->complaint->status)));

        if ($this->type === 'resolved' && $this->complaint->admin_response) {
            $mail->line('Response: ' . $this->complaint->admin_response);
        }

        return $mail->action('View Complaint', url('/complaints/' . $this->complaint->id))
            ->line('Thank you!');
    }

    public function toArray($notifiable)
    {
        return [
            'complaint_id' => $this->complaint->id,
            'complaint_number' => $this->complaint->complaint_number,
            'subject' => $this->complaint->subject,
            'category' => $this->complaint->category,
            'priority' => $this->complaint->priority,
            'status' => $this->complaint->status,
            'type' => $this->type,
        ];
    }
}
