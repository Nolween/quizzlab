<?php

namespace App\Http\Resources;

use App\Http\Resources\QuestionChoices\QuestionChoiceResource;
use App\Http\Resources\QuestionTags\TagResource;
use App\Models\Question;
use App\Models\QuestionVote;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class QuestionIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     */
    public function toArray(Request $request): array|JsonSerializable|Arrayable
    {
        // Conversion de la date de création
        Carbon::setLocale('fr');
        $ago = $this->created_at->diffForHumans(Carbon::now(), true);
        // L'utilisateur est-il connecté ?
        $userId = auth()->id();
        if ($userId) {
            // A-t-il voté pour cette question ?
            $questionVote = QuestionVote::select('has_approved')->where('question_id', $this->id)->where(
                'user_id',
                $userId
            )->first();
        }

        return [
            'id'            => $this->id,
            'question'      => $this->question,
            'choices'       => QuestionChoiceResource::collection($this->choices),
            'vote'          => $this->vote,
            'image'         => $this->image,
            'avatar'        => $this->user->avatar,
            'userName'      => $this->user->name,
            'isIntegrated'  => (bool)$this->is_integrated,
            'tags'          => TagResource::collection($this->tags),
            'commentsCount' => $this->comments->count(),
            'ago'           => $ago,
            'hasVoted'      => $questionVote->has_approved ?? null,
        ];
        // return parent::toArray($request);
    }
}
