<?php

namespace App\Http\Resources\Games;

use App\Models\Game;
use App\Models\GameResult;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class GameResultsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray(Request $request)
    {

        // Récupération de tous les résultats
        $results = ['questions' => []];

        // Questions de la partie
        $gameQuestions = $this->questions;

        foreach ($gameQuestions as $gameQuestion) {
            // Réponse de l'utilisateur à la question
            $userChoix = GameResult::resultQuestionOfUser($gameQuestion->id, auth()->id())->first();
            $choices = $gameQuestion->question->choices;

            $questionChoices = [];
            foreach ($choices as $choice) {
                $userChoices = GameResult::where('choice_id', $choice->id)->where(
                    'game_question_id',
                    $gameQuestion->id
                )->get();
                $userChoicesArray = [];
                foreach ($userChoices as $userChoice) {
                    $userChoicesArray[$userChoice->choice_id] = [
                        'user'   => $userChoice->user->name,
                        'avatar' => $userChoice->user->avatar,
                    ];
                }
                $questionChoices[] = [
                    'title'       => $choice->title,
                    'is_correct'  => $choice->is_correct,
                    'userChoices' => $userChoicesArray,
                ];
            }

            $results['questions'][] = [
                'question'   => $gameQuestion->question->question,
                'isCorrect'  => $userChoice ? $userChoice->is_correct : false,
                'allChoices' => $questionChoices,
                'score'      => $gameQuestion->question->ratio_score,
                'id'         => $gameQuestion->id,
            ];

            // Récupération des scores de la partie

            $results['ladder'] = null;
        }

        return $results;
    }
}
