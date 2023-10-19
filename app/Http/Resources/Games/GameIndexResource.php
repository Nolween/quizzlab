<?php

namespace App\Http\Resources\Games;

use App\Models\Game;
use App\Models\GamePlayer;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class GameIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Game $game
     */
    public function toArray($game): array|JsonSerializable|Arrayable
    {
        // Récupération des tags de la partie
        $tagArray = [];
        $tags = $game->tags;
        foreach ($tags as $tag) {
            $tagArray[] = ['id' => $tag->tag->id, 'name' => $tag->tag->name];
        }
        // Nombre de joueurs dans la partie
        $waitingPlayers = GamePlayer::where('game_id', $game->id)->count();

        return [
            'id'                      => $game->id,
            'questionCount'           => $game->question_count,
            'gameCode'                => $game->game_code,
            'gameRule'                => $game->game_rule_id,
            'waitingPlayers'          => $waitingPlayers,
            'maxPlayers'              => $game->max_players,
            'responseTime'            => $game->response_time,
            'avatar'                  => $game->user->avatar,
            'userName'                => $game->user->name,
            'hasBegun'                => (bool)$game->has_begun,
            'questionStep'            => $game->question_step,
            'questions_have_all_tags' => $game->questions_have_all_tags,
            'tags'                    => $tagArray,
        ];
        // return parent::toArray($request);
    }
}
