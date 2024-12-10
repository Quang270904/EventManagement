<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RegisterEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $user;
    public $event;
    public $regisTrationTime;


    /**
     * Create a new event instance.
     */
    public function __construct($user, $event, $regisTrationTime)
    {
        //
        $this->user = $user;
        $this->event = $event;
        $this->regisTrationTime = $regisTrationTime;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('event-registration'),
        ];
    }

    public function broadcastWith()
    {
        return [
            'message' => "{$this->user->userDetail->full_name} has registered for {$this->event->name}",
            'event_name' => $this->event->name,
            'registration_time' => $this->regisTrationTime->format('Y-m-d H:i:s'),
        ];
    }
}
