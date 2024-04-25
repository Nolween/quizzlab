<?php

namespace App\Events\GamePlayer;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JoiningPlayerEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Infos sur le joueur dans la partie
     *
     * @var \App\Models\GamePlayer
     */
    public $gamePlayer;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($gamePlayer)
    {
        $this->gamePlayer = $gamePlayer;
    }

    /**
     * Le nom de l'évènement
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'game.join';
    }

    /**
     * Ce que va renvoyer l'évènement
     *
     * @return array
     */
    public function broadcastWith()
    {
        $data = [
            'final_place' => $this->gamePlayer->final_place,
            'final_score' => $this->gamePlayer->final_score,
            'game_id' => $this->gamePlayer->game_id,
            'id' => $this->gamePlayer->id,
            'is_ready' => $this->gamePlayer->is_ready,
            'user_id' => $this->gamePlayer->user_id,
            'user' => [
                'avatar' => $this->gamePlayer->user->avatar,
                'id' => $this->gamePlayer->user->id,
                'name' => $this->gamePlayer->user->name,
            ],
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
        return new PrivateChannel('game.'.$this->gamePlayer->game_id);
    }
}
