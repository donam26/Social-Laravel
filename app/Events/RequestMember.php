<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RequestMember implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    private $notification;
    private $group;
    public function __construct($notification ,$group)
    {
        $this->notification = $notification;
        $this->group = $group;
    }

    /** 
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('RequestMember.' . $this->group->created_user);
    }

    public function broadcastWith()
    {
        return [
            'notification' => $this->notification,
            'message' => 'Yêu cầu vào nhóm mới', 
        ];
    }
}
