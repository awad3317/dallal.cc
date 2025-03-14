<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class sentMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $receiverId;
    public $conversation_id;
    public $sender_id;
    /**
     * Create a new event instance.
     */
    public function __construct($message,$receiverId,$conversation_id,$sender_id)
    {
        $this->message=$message;
        $this->receiverId=$receiverId;
        $this->conversation_id=$conversation_id;
        $this->sender_id=$sender_id;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('private-channel.user.'.$this->receiverId),
        ];
    }
    public function broadcastWith():array{
        return[
            'message'=>$this->message,
            'conversation_id'=>$this->conversation_id,
            'sender_id'=>$this->sender_id,
            'timestamp' => now()->toDateTimeString(),
        ];
    }
}
