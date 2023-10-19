<?php

namespace App\Http\Resources\Questions;

use App\Models\GameQuestion;
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
     * @param  GameQuestion  $gameQuestion
     */
    public function toArray($gameQuestion): array
    {
        // Les infos ne doivent être retournées que si la question n'est pas intégrée au quizz
        if ($gameQuestion->is_integrated) {
            return ['forbidden' => true];
        }
        // Si la question n'est pas encore intégrée au quizz, on peut afficher
        else {
            // Conversion de la date de création
            Carbon::setLocale('fr');
            $ago = $gameQuestion->created_at->diffForHumans(Carbon::now(), true);
            // Récupération des tags de la question
            $tagArray = [];
            $tags = $gameQuestion->tags;
            foreach ($tags as $tag) {
                $tagArray[] = ['id' => $tag->tag->id, 'name' => $tag->tag->name];
            }
            $choiceArray = [];
            foreach ($gameQuestion->choices as $choice) {
                $choiceArray[] = ['title' => $choice->title, 'is_correct' => $choice->is_correct];
            }
            // L'utilisateur est-il connecté ?
            $userId = auth()->id();
            if ($userId) {
                // A-t-il voté pour cette question ?
                $questionVote = QuestionVote::select('has_approved')->where('question_id', $gameQuestion->id)->where('user_id', $userId)->first();
            }
            // Construction des commentaires
            foreach ($gameQuestion->primary_comments as $comment) {
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
                'id' => $gameQuestion->id,
                'question' => $gameQuestion->question,
                'choices' => $choiceArray,
                'vote' => $gameQuestion->vote,
                'image' => $gameQuestion->image,
                'avatar' => $gameQuestion->user->avatar,
                'userName' => $gameQuestion->user->name,
                'isIntegrated' => (bool) $gameQuestion->is_integrated,
                'tags' => $tagArray,
                'comments' => $gameQuestion->primary_comments,
                'commentsCount' => $gameQuestion->comments->count(),
                'ago' => $ago,
                'hasVoted' => $questionVote->has_approved ?? null,
            ];
        }
    }
}
