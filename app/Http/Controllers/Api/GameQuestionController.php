<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GameQuestions\GameQuestionShowResource;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\GameQuestion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GameQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        //
        return response();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): Response
    {
        //
        return response($request);
    }

    /**
     * Affichage de la question active de la partie en cours
     */
    public function question(int $gameId): JsonResponse|GameQuestionShowResource
    {
        // Récupération de l'utilisateur
        $userId = auth()->id();
        // Récupération de la partie
        $game = Game::findOrFail($gameId);
        // La partie a-t-elle bien commencé ?
        if (! $game->has_begun) {
            return response()->json(['success' => false, 'message' => "La partie n'a pas encore commencé"], 500);
        }
        // Le joueur est-il bien dans la partie ?
        GamePlayer::where('user_id', $userId)->where('game_id', $game->id)->firstOrFail();
        // Quelle est le numéro de la question active ?
        $skip = max($game->question_step, 0);
        $gameQuestion = GameQuestion::where('game_id', $game->id)->orderBy('order', 'ASC')->skip($skip)->firstOrFail();

        return new GameQuestionShowResource($gameQuestion);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GameQuestion $gameQuestion): Response
    {
        //
        return response($$gameQuestion);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GameQuestion $gameQuestion): Response
    {
        //
        return response($gameQuestion);
    }
}
