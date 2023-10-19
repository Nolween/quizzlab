<?php

namespace App\Http\Resources\Comments;

use App\Models\QuestionComment;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class QuestionCommentUpdateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  QuestionComment  $comment
     */
    public function toArray($comment): array|JsonSerializable|Arrayable
    {
        return parent::toArray($comment);
    }
}
