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
use App\Models\Post;

class NotificationViolate implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    private $notification;
    private $violate;
    public function __construct(Post $violate,Notification $notification)
    {
        $this->violate = $violate;
        $this->notification = $notification;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('NotificationViolate.'. $this->violate->user_id);
    } 
    public function broadcastWith()
    {
        return [
            'notification' => $this->notification,
            'message' => 'Bài viết của bạn đã vi phạm chính sách', 
        ];
    }
}
