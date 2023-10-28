<?php

namespace App\Http\Resources\QuestionComments;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionCommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $userId = auth()->id();
        $responses = $this->responses;

        return [
            'avatar'             => $this->user->avatar,
            'id'                 => $this->id,
            'user_id'            => $this->user_id,
            'question_id'        => $this->question_id,
            'userName'           => $this->user->name,
            'ago'                => $this->updated_at->diffForHumans(Carbon::now(), true),
            'hasReacted'         => $this->userOpinion->has_approved ?? null,
            'comment'            => $this->comment,
            'disapprovals_count' => $this->disapprovals_count,
            'approvals_count'    => $this->approvals_count,
            'ownComment'         => isset($userId) && $this->user_id === $userId ?? null,
            'modified'           => strtotime($this->updated_at) !== strtotime($this->created_at),
            'responses'          => QuestionCommentResource::collection($responses),
        ];
    }
}
