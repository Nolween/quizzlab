<?php

namespace App\Listeners\GameChat;

use App\Events\GameChat\MessageSentEvent;
use App\Models\GameChat;

class RefreshNewMessageListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param MessageSentEvent $event
     * @return void
     */
    public function handle(MessageSentEvent $event): void
    {
        // Quel est la partie du message ?
        $gameId = $event->gameChat->game_id;
        // On récupère tous les messages de la partie
        $data = [];
        $data['chat'] = GameChat::with('user:name,avatar,id')->where('game_id', $gameId)->orderBy('created_at', 'ASC')->get();
    }
}
