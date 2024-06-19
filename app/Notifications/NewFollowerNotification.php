<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewFollowerNotification extends Notification
{
    use Queueable;

    protected $follower;
    protected $followerType;

    /**
     * Create a new notification instance.
     *
     * @param User $follower
     * @param string $followerType
     */
    public function __construct(User $follower, $followerType)
    {
        $this->follower = $follower;
        $this->followerType = $followerType;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database']; // Tersimpan di database untuk ditampilkan di laman web
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'follower_id' => $this->follower->id,
            'follower_name' => $this->follower->name,
            'follower_type' => $this->followerType,
            'message' => 'Anda memiliki pengikut baru.'
        ];
    }
}
