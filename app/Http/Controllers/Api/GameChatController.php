<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GameChats\GameChatStoreRequest;
use App\Http\Resources\GameChats\GameChatStoreResource;
use App\Models\GameChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameChatController extends Controller
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
     * @param  \Illuminate\Http\GameChatStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GameChatStoreRequest $request)
    {
        // Récupération de l'id de l'utilisateur
        $userId = Auth::user()->id;
     
        // Création du message pour la partie
        $newGameChat = GameChat::create(['user_id' => $userId, 'game_id' => $request->gameId, 'text' => $request ->message]);

        return new GameChatStoreResource($newGameChat);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GameChat  $gameChat
     * @return \Illuminate\Http\Response
     */
    public function show(GameChat $gameChat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GameChat  $gameChat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GameChat $gameChat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GameChat  $gameChat
     * @return \Illuminate\Http\Response
     */
    public function destroy(GameChat $gameChat)
    {
        //
    }
}
