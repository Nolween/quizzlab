<?php

namespace App\Http\Resources\Comments;

use Illuminate\Http\Resources\Json\JsonResource;

class QuestionCommentUpdateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \App\Models\QuestionComment  $comment
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($comment)
    {
        return parent::toArray($comment);
    }
}
