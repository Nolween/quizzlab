<?php

namespace App\Events\Game;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BeginningGameEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $game;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($game)
    {
        $this->game = $game;
    }

    /**
     * Définition du nom de l'évènement
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'game.begin';
    }

    /**
     * Ce que va retourner l'évènement au fron,t
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'created_at' => $this->game->created_at,
            'game_code' => $this->game->game_code,
            'game_rule_id' => $this->game->game_rule_id,
            'has_begun' => $this->game->has_begun,
            'id' => $this->game->id,
            'is_finished' => $this->game->is_finished,
            'max_players' => $this->game->max_players,
            'question_count' => $this->game->question_count,
            'question_step' => $this->game->question_step,
            'questions_have_all_tags' => $this->game->questions_have_all_tags,
            'response_time' => $this->game->response_time,
            'updated_at' => $this->game->updated_at,
            'user' => [
                'avatar' => $this->game->user->avatar,
                'id' => $this->game->user->id,
                'name' => $this->game->user->name,
            ],
            'user_id' => $this->game->user_id,
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('game.'.$this->game->id);
    }
}
