<?php

namespace App\Http\Resources\QuestionTags;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=> $this->tag->id,
            'name' => $this->tag->name,
        ];
    }
}
