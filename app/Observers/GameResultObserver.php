<?php

namespace App\Observers;

use App\Models\GameQuestion;
use App\Models\GameResult;
use App\Models\QuestionChoice;

class GameResultObserver
{
    /**
     * Handle the GameResult "creating" event.
     *
     * @param GameResult $gameResult
     * @return void
     */
    public function creating(GameResult $gameResult): void
    {
        // Attribution de l'utilisateur
        $gameResult->user_id = auth()->id();
        // Vérification si la réponse choisie est correcte
        $gameQuestion = GameQuestion::find($gameResult->game_question_id);
        $goodQuestionChoiceId = QuestionChoice::where('question_id', $gameQuestion->question_id)->where('is_correct', true)->first()->id;
        $gameResult->is_correct = $goodQuestionChoiceId == $gameResult->choice_id;
        // Attribution du score si le résultat est bon
        $gameResult->score = $gameResult->is_correct ? $gameQuestion->question->ratio_score : 0;
    }

    /**
     * Handle the GameResult "created" event.
     *
     * @param GameResult $gameResult
     * @return void
     */
    public function created(GameResult $gameResult): void
    {
        //
    }

    /**
     * Handle the GameResult "updated" event.
     *
     * @param GameResult $gameResult
     * @return void
     */
    public function updated(GameResult $gameResult): void
    {
        //
    }

    /**
     * Handle the GameResult "deleted" event.
     *
     * @param GameResult $gameResult
     * @return void
     */
    public function deleted(GameResult $gameResult): void
    {
        //
    }

    /**
     * Handle the GameResult "restored" event.
     *
     * @param GameResult $gameResult
     * @return void
     */
    public function restored(GameResult $gameResult): void
    {
        //
    }

    /**
     * Handle the GameResult "force deleted" event.
     *
     * @param GameResult $gameResult
     * @return void
     */
    public function forceDeleted(GameResult $gameResult): void
    {
        //
    }
}
