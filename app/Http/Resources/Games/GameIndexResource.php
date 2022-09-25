<?php

namespace App\Http\Resources\Games;

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
     * @param  Request  $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request): array|JsonSerializable|Arrayable
    {
        // Récupération des tags de la partie
        $tagArray = [];
        $tags = $this->tags;
        foreach ($tags as $tag) {
            $tagArray[] = ['id' => $tag->tag->id, 'name' => $tag->tag->name];
        }
        // Nombre de joueurs dans la partie
        $waitingPlayers = GamePlayer::where('game_id', $this->id)->get()->count();
        return [
            'id' => $this->id,
            'questionCount' => $this->question_count,
            'gameCode' => $this->game_code,
            'gameRule' => $this->game_rule_id,
            'waitingPlayers' => $waitingPlayers,
            'maxPlayers' => $this->max_players,
            'responseTime' => $this->response_time,
            'avatar' => $this->user->avatar,
            'userName' => $this->user->name,
            'hasBegun' => (boolean)$this->has_begun,
            'questionStep' => $this->question_step,
            'questions_have_all_tags' => $this->questions_have_all_tags,
            'tags' => $tagArray,
        ];
        // return parent::toArray($request);
    }
}
