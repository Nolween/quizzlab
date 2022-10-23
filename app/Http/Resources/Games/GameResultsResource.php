<?php

namespace App\Http\Resources\Games;

use App\Models\GameResult;
use App\Models\QuestionChoice;
use Illuminate\Http\Resources\Json\JsonResource;

class GameResultsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // $data = [];
        // foreach ($this->gameQuestion as $question) {
        //
        // }

        // Récupération de tous les résultats
        $results = ['questions' => []];

        // Questions de la partie
        $gameQuestions = $this->questions;

        foreach ($gameQuestions as $gameQuestion) {

            // // Réponse de l'utilisateur à la question
            $userChoice = GameResult::where('user_id', auth()->id())->where('game_question_id', $gameQuestion->id)->first();
            $choices = $gameQuestion->question->choices;

            $questionChoices = [];
            foreach ($choices as $choice) {
                $userChoices = GameResult::where('choice_id', $choice->id)->where('game_question_id', $gameQuestion->id)->get();
                $userChoicesArray = [];
                foreach ($userChoices as $userChoice) {
                    $userChoicesArray[$userChoice->choice_id] = [
                        'user' => $userChoice->user->name,
                        'avatar' => $userChoice->user->avatar
                    ];
                }
                $questionChoices[] = [
                    'title' => $choice->title,
                    'is_correct' => $choice->is_correct,
                    'userChoices' => $userChoicesArray
                ];
            }


            $results['questions'][] = [
                'question' => $gameQuestion->question->question,
                'isCorrect' => $userChoice ? $userChoice->is_correct : false,
                'allChoices' => $questionChoices,
                'score' => $gameQuestion->question->ratio_score,
                'id' => $gameQuestion->id
            ];

            // Récupération des scores de la partie

            $results['ladder'] = null;
        }

        return $results;

    }
}
