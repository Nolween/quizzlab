<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GameQuestions\GameQuestionShowResource;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\GameQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Affichage de la question active de la partie en cours
     *
     * @param  int $gameId
     * @return \Illuminate\Http\Response
     */
    public function question(int $gameId)
    {
        // Récupération de l'utilisateur
        $userId = Auth::user()->id;
        // Récupération de la partie
        $game = Game::findOrFail($gameId);
        // La partie a t-elle bien commencé?
        if($game->has_begun == false) {
            return response()->json(['success' => false, 'message' => "La partie n'a pas encore commencé"], 500);
        }
        // Le joueur est-il bien dans la partie?
        GamePlayer::where('user_id', $userId)->where('game_id', $game->id)->firstOrFail();
        // Quelle est le numéro de la question active?
        $skip = $game->question_step > 1 ? $game->question_step - 1 : 0;
        $gameQuestion = GameQuestion::with(['question', 'game', 'questionTags'])->where('game_id', $game->id)->orderBy('order', 'ASC')->skip($skip)->first();

        return new GameQuestionShowResource($gameQuestion);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GameQuestion  $gameQuestion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GameQuestion $gameQuestion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GameQuestion  $gameQuestion
     * @return \Illuminate\Http\Response
     */
    public function destroy(GameQuestion $gameQuestion)
    {
        //
    }
}
