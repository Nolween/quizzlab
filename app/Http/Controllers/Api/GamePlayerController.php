<?php

namespace App\Http\Controllers\Api;

use App\Events\GamePlayer\LeavingPlayerEvent;
use App\Events\GamePlayer\UpdatedStatusEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\GamePlayers\GamePlayerDestroyRequest;
use App\Http\Requests\GamePlayers\GamePlayerReadyRequest;
use App\Models\Game;
use App\Models\GamePlayer;
use Illuminate\Http\Request;

class GamePlayerController extends Controller
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
     * Display the specified resource.
     *
     * @param  \App\Models\GamePlayer  $gamePlayer
     * @return \Illuminate\Http\Response
     */
    public function show(GamePlayer $gamePlayer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GamePlayer  $gamePlayer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GamePlayer $gamePlayer)
    {
        //
    }

    /**
     * Mise à jour du statut prêt pour le joueur dans la partie
     *
     * @param  \App\Http\Requests\GamePlayers\GamePlayerReadyRequest  $request
     * @param  \App\Models\GamePlayer  $gamePlayer
     * @return \Illuminate\Http\Response
     */
    public function ready(GamePlayerReadyRequest $request)
    {
        // Récupération des infos du joueur dans la partie
        $gamePlayer = GamePlayer::where('game_id', $request->gameId)->where('user_id', $request->userId)->firstOrFail();
        // Switch true false selon le statut précédent
        $gamePlayer->is_ready = !$gamePlayer->is_ready;
        $gamePlayer->save();

        // Déclenchement d'un évènement pour le serveur websocket
        event(new UpdatedStatusEvent($gamePlayer));

        return response()->json(['success' => true], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Http\Requests\GamePlayers\GamePlayerDestroyRequest $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(GamePlayerDestroyRequest $request)
    {
        $gamePlayer = GamePlayer::findOrFail($request->gamePlayerId);
        // dump($gamePlayer);
        // Récupération des données de la partie
        $game = Game::findOrFail($gamePlayer->game_id);
        // Si la partie n'a pas encore commencé
        if($game->has_begun == false) {
            // Evénement websocket pour mettre à jour la liste des joueurs présents dans la partie
            event(new LeavingPlayerEvent($gamePlayer));
            // On vire le joueur de la partie
            $gamePlayer->delete();
            return response()->json(['success' => true], 200);
        }

    }
}
