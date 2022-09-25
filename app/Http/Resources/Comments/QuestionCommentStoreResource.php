<?php

namespace App\Http\Resources\Comments;

use App\Models\QuestionComment;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class QuestionCommentStoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  QuestionComment  $newComment
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($newComment): array|JsonSerializable|Arrayable
    {
        return parent::toArray($newComment);
    }
}
