<?php

namespace App\Observers;

use App\Models\GameQuestion;
use App\Models\GameResult;
use App\Models\QuestionChoice;
use Illuminate\Support\Facades\Auth;

class GameResultObserver
{
    /**
     * Handle the GameResult "creating" event.
     *
     * @param  \App\Models\GameResult  $gameResult
     * @return void
     */
    public function creating(GameResult $gameResult)
    {
        // Attribution de l'utilisateur
        $gameResult->user_id = Auth::user()->id;
        // Vérification si la réponse choisie est correcte
        $gameQuestion = GameQuestion::find($gameResult->game_question_id);
        $goodQuestionChoiceId = QuestionChoice::where('question_id', $gameQuestion->question_id)->where('is_correct', true)->first()->id;
        $gameResult->is_correct = $goodQuestionChoiceId == $gameResult->choice_id ? true : false;
        // Attribution du score si le résultat est bon
        $gameResult->score = $gameResult->is_correct == true ? $gameQuestion->question->ratio_score : 0;
    }

    /**
     * Handle the GameResult "created" event.
     *
     * @param  \App\Models\GameResult  $gameResult
     * @return void
     */
    public function created(GameResult $gameResult)
    {
        //
    }

    /**
     * Handle the GameResult "updated" event.
     *
     * @param  \App\Models\GameResult  $gameResult
     * @return void
     */
    public function updated(GameResult $gameResult)
    {
        //
    }

    /**
     * Handle the GameResult "deleted" event.
     *
     * @param  \App\Models\GameResult  $gameResult
     * @return void
     */
    public function deleted(GameResult $gameResult)
    {
        //
    }

    /**
     * Handle the GameResult "restored" event.
     *
     * @param  \App\Models\GameResult  $gameResult
     * @return void
     */
    public function restored(GameResult $gameResult)
    {
        //
    }

    /**
     * Handle the GameResult "force deleted" event.
     *
     * @param  \App\Models\GameResult  $gameResult
     * @return void
     */
    public function forceDeleted(GameResult $gameResult)
    {
        //
    }
}
