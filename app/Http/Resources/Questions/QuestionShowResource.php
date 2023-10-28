<?php

namespace App\Http\Resources\Questions;

use App\Http\Resources\QuestionChoices\QuestionChoiceResource;
use App\Http\Resources\QuestionComments\QuestionCommentResource;
use App\Http\Resources\QuestionTags\TagResource;
use App\Http\Resources\Tags\TagIndexResource;
use App\Models\GameQuestion;
use App\Models\Question;
use App\Models\QuestionComment;
use App\Models\QuestionVote;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     */
    public function toArray(Request $request): array
    {
        // Si la question n'est pas encore intégrée au quizz, on peut afficher
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
            'comments'      => QuestionCommentResource::collection($this->primary_comments),
            'commentsCount' => $this->comments->count(),
            'ago'           => $ago,
            'hasVoted'      => $questionVote->has_approved ?? null,
        ];
    }

}
