<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Notification;
use App\Models\Friend;

class SendRequestAddFriend implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    private $notification;
    private $friendShips;
    public function __construct(Friend $friendShips,Notification $notification)
    {
        $this->friendShips = $friendShips;
        $this->notification = $notification;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('RequestAddFriend.'. $this->friendShips->friend_id);
    } 
    public function broadcastWith()
    {
        return [
            'notification' => $this->notification,
            'message' => 'Bạn có yêu cầu kết bạn', 
        ];
    }
}
