<?php

namespace App\Http\Resources\Approvals;

use App\Models\QuestionComment;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentApprovalsStoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  object $response
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($response)
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
