<?php

namespace App\Http\Controllers\Api;

use App\Events\GamePlayer\LeavingPlayerEvent;
use App\Events\GamePlayer\UpdatedStatusEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\GamePlayers\GamePlayerDestroyRequest;
use App\Http\Requests\GamePlayers\GamePlayerReadyRequest;
use App\Models\Game;
use App\Models\GamePlayer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GamePlayerController extends Controller
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
     * Display the specified resource.
     */
    public function show(GamePlayer $gamePlayer): Response
    {
        //
        return response($$gamePlayer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GamePlayer $gamePlayer): Response
    {
        //
        return response($$gamePlayer);
    }

    /**
     * Mise à jour du statut prêt pour le joueur dans la partie
     */
    public function ready(GamePlayerReadyRequest $request): JsonResponse
    {
        // Récupération des infos du joueur dans la partie
        $gamePlayer = GamePlayer::where('game_id', $request->gameId)->where('user_id', $request->userId)->firstOrFail();
        // Switch true false selon le statut précédent
        $gamePlayer->is_ready = ! $gamePlayer->is_ready;
        $gamePlayer->save();

        // Déclenchement d'un évènement pour le serveur websocket
        event(new UpdatedStatusEvent($gamePlayer));

        return response()->json(['success' => true]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GamePlayerDestroyRequest $request): JsonResponse
    {
        $gamePlayer = GamePlayer::findOrFail($request->gamePlayerId);
        // dump($gamePlayer);
        // Récupération des données de la partie
        $game = Game::findOrFail($gamePlayer->game_id);
        // Si la partie n'a pas encore commencé
        if (! $game->has_begun) {
            // Événement websocket pour mettre à jour la liste des joueurs présents dans la partie
            event(new LeavingPlayerEvent($gamePlayer));
            // On vire le joueur de la partie
            $gamePlayer->delete();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);

    }
}
