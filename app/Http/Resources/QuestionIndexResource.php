<?php

namespace App\Http\Resources;

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
     * @param Question $question
     */
    public function toArray($question): array|JsonSerializable|Arrayable
    {
        // Conversion de la date de création
        Carbon::setLocale('fr');
        $ago = $question->created_at->diffForHumans(Carbon::now(), true);
        // Récupération des tags de la question
        $tagArray = [];
        $tags = $question->tags;
        foreach ($tags as $tag) {
            $tagArray[] = ['id' => $tag->tag->id, 'name' => $tag->tag->name];
        }
        $choiceArray = [];
        foreach ($question->choices as $choice) {
            $choiceArray[] = ['title' => $choice->title, 'is_correct' => $choice->is_correct];
        }
        // L'utilisateur est-il connecté ?
        $userId = auth()->id();
        if ($userId) {
            // A-t-il voté pour cette question ?
            $questionVote = QuestionVote::select('has_approved')->where('question_id', $question->id)->where(
                'user_id',
                $userId
            )->first();
        }

        return [
            'id'            => $question->id,
            'question'      => $question->question,
            'choices'       => $choiceArray,
            'vote'          => $question->vote,
            'image'         => $question->image,
            'avatar'        => $question->user->avatar,
            'userName'      => $question->user->name,
            'isIntegrated'  => (bool)$question->is_integrated,
            'tags'          => $tagArray,
            'commentsCount' => $question->comments->count(),
            'ago'           => $ago,
            'hasVoted'      => $questionVote->has_approved ?? null,
        ];
        // return parent::toArray($request);
    }
}
