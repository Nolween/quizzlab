<?php

namespace App\Http\Resources\GameChats;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class GameChatStoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     */
    public function toArray($request): array|JsonSerializable|Arrayable
    {
        return parent::toArray($request);
    }
}
