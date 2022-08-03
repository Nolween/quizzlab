<?php

namespace App\Http\Resources\Questions;

use App\Models\QuestionComment;
use App\Models\QuestionVote;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class QuestionShowResource extends JsonResource
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
        // L'utilisateur est-il connecté?
        $user = Auth::user();
        if($user) {
            // A t-il voté pour cette question?
            $questionVote = QuestionVote::select('has_approved')->where('question_id', $this->id)->where('user_id', $user->id)->first();
        }
        // Construction des commentaires
        foreach ($this->primary_comments as $comment) {
            $comment['avatar'] = $comment->user->avatar;
            $comment['userName'] = $comment->user->name;
            $comment['ago'] = $comment->updated_at->diffForHumans(Carbon::now(), true);
            $comment['hasReacted'] = $comment->userOpinion->has_approved ?? null;
            $comment['ownComment'] = isset($user->id) && $comment->user_id === $user->id ?? null;
            $comment['modified'] = strtotime($comment->updated_at) !== strtotime($comment->created_at);
            // Le commentaire a t-il eu des réponses?
            $responses = QuestionComment::where('comment_id', $comment->id)->orderBy('created_at', 'ASC')->get();
            foreach ($responses as $response) {
                $response['avatar'] = $response->user->avatar;
                $response['userName'] = $response->user->name;
                $response['ago'] = $response->updated_at->diffForHumans(Carbon::now(), true);
                $response['hasReacted'] = $response->userOpinion->has_approved ?? null;
                $response['ownComment'] = isset($user->id) && $response->user_id === $user->id ?? null;
                $response['modified'] = strtotime($response->updated_at) !== strtotime($response->created_at);
            }
            $comment['responses'] = $responses;
        };
        return [
            'id' => $this->id,
            'question' => $this->question,
            'answer' => $this->answer,
            'vote' => $this->vote,
            'avatar' => $this->user->avatar,
            'userName' => $this->user->name,
            'tags' => $tagArray,
            'comments' => $this->primary_comments,
            'commentsCount' => $this->comments->count(),
            'ago' => $ago,
            'hasVoted' => $questionVote->has_approved ?? null,
        ];

    }
}
