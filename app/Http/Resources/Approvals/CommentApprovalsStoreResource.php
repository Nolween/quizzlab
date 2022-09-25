<?php

namespace App\Http\Resources\Approvals;

use App\Models\QuestionComment;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class CommentApprovalsStoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  object $response
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($response): array|JsonSerializable|Arrayable
    {
        $comment = QuestionComment::findOrFail($response->commentid);
        return [
            'commentid' => $response->commentid,
            'ispositive' => $response->ispositive,
            'approvals_count' => $comment->approvals_count,
            'disapprovals_count' => $comment->disapprovals_count
        ];
    }
}
