<?php

namespace App\Http\Resources\Games;

use App\Models\Game;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class GameStoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     */
    public function toArray(Request $request): array|JsonSerializable|Arrayable
    {
        return parent::toArray($request);
    }
}
