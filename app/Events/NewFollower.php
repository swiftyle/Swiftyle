<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewFollower
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $follower;
    public $followableId;
    public $followableType;

    /**
     * Create a new event instance.
     *
     * @param User $follower
     * @param int $followableId
     * @param string $followableType
     */
    public function __construct(User $follower, $followableId, $followableType)
    {
        $this->follower = $follower;
        $this->followableId = $followableId;
        $this->followableType = $followableType;
    }
}
