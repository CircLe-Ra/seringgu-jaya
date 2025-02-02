<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LetterProcessEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $letter;
    public $position;
    public $name;
    public $profile_photo;
    public $message;
    /**
     * Create a new event instance.
     */
    public function __construct($userId, $letter, $position, $name, $profile_photo, $message)
    {
        $this->userId = $userId;
        $this->letter = $letter;
        $this->position = $position;
        $this->name = $name;
        $this->profile_photo = $profile_photo;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('notification-for-neighborhood-and-citizen.'.$this->userId),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'userId' => $this->userId,
            'letter' => $this->letter,
            'position' => $this->position,
            'name' => $this->name,
            'profile_photo' => $this->profile_photo,
            'message' => $this->message
        ];
    }
}
