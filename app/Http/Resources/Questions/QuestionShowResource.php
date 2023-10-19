<?php

namespace App\Http\Resources\Questions;

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
     * @param  Request  $request
     */
    public function toArray($request): array
    {
        // Les infos ne doivent être retournées que si la question n'est pas intégrée au quizz
        if ($this->is_integrated) {
            return ['forbidden' => true];
        }
        // Si la question n'est pas encore intégrée au quizz, on peut afficher
        else {
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
            // L'utilisateur est-il connecté ?
            $userId = auth()->id();
            if ($userId) {
                // A-t-il voté pour cette question ?
                $questionVote = QuestionVote::select('has_approved')->where('question_id', $this->id)->where('user_id', $userId)->first();
            }
            // Construction des commentaires
            foreach ($this->primary_comments as $comment) {
                $comment['avatar'] = $comment->user->avatar;
                $comment['userName'] = $comment->user->name;
                $comment['ago'] = $comment->updated_at->diffForHumans(Carbon::now(), true);
                $comment['hasReacted'] = $comment->userOpinion->has_approved ?? null;
                $comment['ownComment'] = isset($userId) && $comment->user_id === $userId ?? null;
                $comment['modified'] = strtotime($comment->updated_at) !== strtotime($comment->created_at);
                // Le commentaire a-t-il eu des réponses ?
                $responses = QuestionComment::where('comment_id', $comment->id)->orderBy('created_at', 'ASC')->get();
                foreach ($responses as $response) {
                    $response['avatar'] = $response->user->avatar;
                    $response['userName'] = $response->user->name;
                    $response['ago'] = $response->updated_at->diffForHumans(Carbon::now(), true);
                    $response['hasReacted'] = $response->userOpinion->has_approved ?? null;
                    $response['ownComment'] = isset($userId) && $response->user_id === $userId ?? null;
                    $response['modified'] = strtotime($response->updated_at) !== strtotime($response->created_at);
                }
                $comment['responses'] = $responses;
            }

            return [
                'id' => $this->id,
                'question' => $this->question,
                'choices' => $choiceArray,
                'vote' => $this->vote,
                'image' => $this->image,
                'avatar' => $this->user->avatar,
                'userName' => $this->user->name,
                'isIntegrated' => (bool) $this->is_integrated,
                'tags' => $tagArray,
                'comments' => $this->primary_comments,
                'commentsCount' => $this->comments->count(),
                'ago' => $ago,
                'hasVoted' => $questionVote->has_approved ?? null,
            ];
        }
    }
}
