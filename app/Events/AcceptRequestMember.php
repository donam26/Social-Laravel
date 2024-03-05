<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Group;
use App\Models\User;
use App\Models\Notification;
 class AcceptRequestMember implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $user;
    public $group;
    public $notification;
    public function __construct(User $user, Group $group, Notification $notification)
    {
        $this->notification = $notification;
        $this->user = $user;
        $this->group = $group;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('AcceptRequestMember.' . $this->user->id);
    }

    public function broadcastWith()
    {
        // Truyền dữ liệu cần gửi đi
        return [
            'group' => $this->group,
            'notification' => $this->notification,
            'message' => 'Yêu cầu vào nhóm ' . $this->group->name .'đã được chấp nhận', 
        ];
    }
}
