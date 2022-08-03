<?php

namespace App\Http\Resources\Comments;

use Illuminate\Http\Resources\Json\JsonResource;

class QuestionCommentStoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \App\Models\QuestionComment  $newComment
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($newComment)
    {
        return parent::toArray($newComment);
    }
}
