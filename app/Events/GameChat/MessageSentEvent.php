<?php

namespace App\Events\GameChat;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

//! Ne pas oublier implements ShouldBroadcast pour permettre la diffusion dans le front
class MessageSentEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    /**
     * Le message envoyé
     *
     * @var \App\Models\GameChat
     */
    public $gameChat;

    /**
     * @param \App\Models\GameChat $gameChat
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($gameChat)
    {
        $this->gameChat = $gameChat;
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'message.sent';
    }


    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        $data = [
            'created_at' => $this->gameChat->created_at,
            'game_id' => $this->gameChat->game_id,
            'id' => $this->gameChat->id,
            'text' => $this->gameChat->text,
            'updated_at' => $this->gameChat->updated_at,
            'user_id' => $this->gameChat->user_id,
            'user' => [
                'avatar' => $this->gameChat->user->avatar,
                'id' => $this->gameChat->user->id,
                'name' => $this->gameChat->user->name
            ]
        ];
        return $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\PrivateChannel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('game.' . $this->gameChat->game_id);
    }
}
