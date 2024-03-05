<?php

namespace App\Events;

use App\Models\Group;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
class ToUser implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $user;
    public $group;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, Group $group)
    {
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
        return new PrivateChannel('ToUser.' . $this->user->id);

    }

    public function broadcastWith()
    {
        // Truyền dữ liệu cần gửi đi
        return [
            'group' => $this->group,
            'message' => 'Bạn đã được thêm vào nhóm ' . $this->group->name, 
        ];
    }
}
