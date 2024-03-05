<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use App\Models\Group;
use App\Models\User;
use App\Models\Notification;

class NotificationMember implements ShouldBroadcast
{

    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $user;
    public $group;
    public $notification;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, Group $group, Notification $notification)
    {
        $this->user = $user;
        $this->group = $group;
        $this->notification = $notification;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn() 
    {
        return new PrivateChannel('NotificationMember.' . $this->user->id);

    }

    public function broadcastWith()
    {
        // Truyền dữ liệu cần gửi đi
        return [
            'group' => $this->group,
            'notification' => $this->notification,
            'message' => Auth::user()->name . ' đã mời bạn vào nhóm ' . $this->group->name, 
        ];
    }
}
