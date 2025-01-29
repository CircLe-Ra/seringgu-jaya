<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LetterApply implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $letter;
    public $position;
    public $name;
    public $profile_photo;
    /**
     * Create a new event instance.
     */
    public function __construct($letter, $position, $name, $profile_photo)
    {
        $this->letter = $letter;
        $this->position = $position;
        $this->name = $name;
        $this->profile_photo = $profile_photo;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return [
            new PrivateChannel('notification-for-staff'),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'letter' => $this->letter,
            'position' => $this->position,
            'name' => $this->name,
            'profile_photo' => $this->profile_photo
        ];
    }
}
