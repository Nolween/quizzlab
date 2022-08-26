<?php

namespace App\Http\Resources\Games;

use Illuminate\Http\Resources\Json\JsonResource;

class GameStoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \App\Models\Game  $newGame
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($newGame)
    {
        return parent::toArray($newGame);
    }
}
