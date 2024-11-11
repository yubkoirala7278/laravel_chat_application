<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MessageSendNotification extends Notification
{
    use Queueable;
    public $message;
    public $sender_id;
    public $receiver_id;

    /**
     * Create a new notification instance.
     */
    public function __construct($message,$sender_id,$receiver_id)
    {
        $this->message=$message;
        $this->sender_id=$sender_id;
        $this->receiver_id=$receiver_id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['database','broadcast'];
    }

    

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable)
    {
        return [
            'message'=>$this->message,
            'sender_id'=>$this->sender_id,
            'receiver_id'=>$this->receiver_id
        ];
    }
}
