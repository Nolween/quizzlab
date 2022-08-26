<?php

namespace App\Http\Resources\Games;

use App\Models\Game;
use App\Models\GameChat;
use App\Models\GamePlayer;
use App\Models\GameTag;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class GameJoinResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        // Partie
        $game = parent::toArray($request);
        $gameId = $game['id'];
        // Partie
        $data = [];
        $data['game'] = Game::with('user:id,name,avatar')->where('id', $gameId)->first();
        // Joueurs
        $data['players'] = GamePlayer::with('user:name,avatar,id')->where('game_id', $gameId)->get();
        // Thèmes
        $data['tags'] = GameTag::with('tag')->where('game_id', $gameId)->get();
        // Discussion
        $data['chat'] = GameChat::with('user:name,avatar,id')->where('game_id', $gameId)->orderBy('created_at', 'ASC')->get();
        $data['userId'] = Auth::id();

        return $data;
    }
}
