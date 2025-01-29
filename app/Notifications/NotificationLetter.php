<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotificationLetter extends Notification
{
    use Queueable;

    public $letter;
    public $position;
    public $name;
    public $profile_photo;

    /**
     * Create a new notification instance.
     */
    public function __construct($letter, $position, $name, $profile_photo)
    {
        $this->letter = $letter;
        $this->position = $position;
        $this->name = $name;
        $this->profile_photo = $profile_photo;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database','broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'letter' => $this->letter,
            'position' => $this->position,
            'name' => $this->name,
            'profile_photo' => $this->profile_photo
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'letter' => $this->letter,
            'position' => $this->position,
            'name' => $this->name,
            'profile_photo' => $this->profile_photo
        ]);
    }
}
