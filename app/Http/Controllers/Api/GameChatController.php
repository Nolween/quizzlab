<?php

namespace App\Http\Controllers\Api;

use App\Events\GameChat\MessageSentEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\GameChats\GameChatStoreRequest;
use App\Http\Resources\GameChats\GameChatStoreResource;
use App\Models\GameChat;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GameChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        //
        return response();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  GameChatStoreRequest  $request
     * @return GameChatStoreResource
     */
    public function store(GameChatStoreRequest $request): GameChatStoreResource
    {
        // Récupération de l'id de l'utilisateur
        $userId = auth()->id();

        // Création du message pour la partie
        $newGameChat = GameChat::create(['user_id' => $userId, 'game_id' => $request->gameId, 'text' => $request ->message]);

        event(new MessageSentEvent($newGameChat));

        return new GameChatStoreResource($newGameChat);
    }

    /**
     * Display the specified resource.
     *
     * @param  GameChat  $gameChat
     * @return Response
     */
    public function show(GameChat $gameChat): Response
    {
        //
        return response($gameChat);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  GameChat  $gameChat
     * @return Response
     */
    public function update(Request $request, GameChat $gameChat): Response
    {
        //
        return response($gameChat);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  GameChat  $gameChat
     * @return Response
     */
    public function destroy(GameChat $gameChat): Response
    {
        //
        return response($gameChat);
    }
}
