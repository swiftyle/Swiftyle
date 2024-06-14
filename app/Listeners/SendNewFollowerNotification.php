<?php

namespace App\Listeners;

use App\Events\NewFollower;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewFollowerNotification;

class SendNewFollowerNotification implements ShouldQueue
{
    public function handle(NewFollower $event)
    {
        $follower = $event->follower;
        $followableId = $event->followableId;
        $followableType = $event->followableType;

        // Determine the followable entity (shop or user)
        switch ($followableType) {
            case 'shop':
                $followable = User::find($followableId);
                break;
            case 'user':
                $followable = User::find($followableId);
                break;
            default:
                return; // Invalid followable type, do nothing
        }

        // Send notification to the followable entity
        if ($followable) {
            $followable->notify(new NewFollowerNotification($follower, $followableType));
        }
    }
}
