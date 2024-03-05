<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Conversation;

class AddGroupToUser implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    private $participant;
    private $conversation;
    /**
     * Create a new event instance.
     *
     * @param Conversation $conversation
     * @param int $participant
     * @return void
     */
    public function __construct(Conversation $conversation, $participant)
    {
        $this->conversation = $conversation;
        $this->participant = $participant;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('AddGroupToUser.' . $this->participant);
    }

    public function broadcastWith()
    {
        return [
            'conversation' => $this->conversation,
        ];
    }
}
