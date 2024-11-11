<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSend implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $user; // The user sending the message
    public $message; // The actual message content
    public $receiverId; // The recipient of the message
    public $senderId;

    /**
     * Create a new event instance.
     *
     * @param $user The user sending the message
     * @param $message The message content
     * @param $receiverId The user receiving the message
     */
    public function __construct($user, $message, $receiverId,$senderId)
    {
        $this->user = $user;
        $this->message = $message;
        $this->receiverId = $receiverId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return new Channel('my-messenger');
    }

    public function broadcastAs(){
        return 'message-submitted';
    }
}
