<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TestPusherEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $receiverId;
    public $conversation_id;
    public $sender_id;
    public $message_id;
    /**
     * Create a new event instance.
     */
    public function __construct($message,$receiverId,$conversation_id,$sender_id,$message_id)
    {
        $this->message=$message;
        $this->receiverId=$receiverId;
        $this->conversation_id=$conversation_id;
        $this->sender_id=$sender_id;
        $this->message_id=$message_id;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return new Channel('testChannel'.$this->receiverId);
    }

    public function broadcastWith():array
    {
        return [
            'id'=>$this->message_id,
            'message_text'=>$this->message,
            'conversation_id'=>$this->conversation_id,
            'sender_id'=>$this->sender_id,
            'receiver_id'=>$this->receiverId,
            'sent_at' => now()->toDateTimeString(),
        ];
    }
}
