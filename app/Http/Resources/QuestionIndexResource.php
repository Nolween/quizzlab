<?php

namespace App\Http\Resources;

use App\Models\QuestionChoice;
use App\Models\QuestionVote;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class QuestionIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // Conversion de la date de création
        Carbon::setLocale('fr');
        $ago = $this->created_at->diffForHumans(Carbon::now(), true);
        // Récupération des tags de la question
        $tagArray = [];
        $tags = $this->tags;
        foreach ($tags as $tag) {
            $tagArray[] = ['id' => $tag->tag->id, 'name' => $tag->tag->name];
        }
        $choiceArray = [];
        foreach ($this->choices as $choice) {
            $choiceArray[] = ['title' => $choice->title, 'is_correct' => $choice->is_correct];
        }
        // L'utilisateur est-il connecté?
        $user = Auth::user();
        if($user) {
            // A t-il voté pour cette question?
            $questionVote = QuestionVote::select('has_approved')->where('question_id', $this->id)->where('user_id', $user->id)->first();
        }
        return [
            'id' => $this->id,
            'question' => $this->question,
            'choices' => $choiceArray,
            'vote' => $this->vote,
            'image' => $this->image,
            'avatar' => $this->user->avatar,
            'userName' => $this->user->name,
            'isIntegrated' => (bool)$this->is_integrated,
            'tags' => $tagArray,
            'commentsCount' => $this->comments->count(),
            'ago' => $ago,
            'hasVoted' => $questionVote->has_approved ?? null
        ];
        // return parent::toArray($request);
    }
}
