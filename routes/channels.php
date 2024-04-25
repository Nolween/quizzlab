<?php

use App\Models\GamePlayer;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
// Modification du statut d'un joueur sur une partie
Broadcast::channel('game.{gameId}', function ($user, $gameId) {
    // Il faut que l'utilisateur soit dans la partie pour accéder au channel
    $gamePlayer = GamePlayer::where('game_id', $gameId)->where('user_id', $user->id)->first();

    return ! empty($gamePlayer) && $user->id === $gamePlayer->user_id;
});
