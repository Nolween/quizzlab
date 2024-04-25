<?php

namespace App\Http\Resources\Comments;

use App\Models\QuestionComment;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class QuestionCommentUpdateResource extends JsonResource
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
