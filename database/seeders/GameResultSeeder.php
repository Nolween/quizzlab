<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\GameQuestion;
use App\Models\GameResult;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GameResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Parcours de toutes les parties
        $games = Game::all();
        foreach ($games as $game) {
            // Si la partie a bien commencé
            if ($game->has_begun == true) {
                // Parcours de tous les joueurs de la partie
                foreach ($game->players as $player) {
                    $finalScore = 0;
                    // Pour chaque question de la partie déjà posée
                    for ($i = 1; $i < $game->question_step; $i++) {
                        // Récupération de la question selon son ordre
                        $gameQuestion = GameQuestion::where('game_id', $game->id)->where('order', $i)->first();
                        // Une chance sur 2 de bien répondre
                        $isCorrect = rand(0, 1);
                        $response = $isCorrect == 1 ? $gameQuestion->question->answer : fake()->sentence(1);
                        // Récupération du score de la question défini
                        $score = $gameQuestion->question->ratio_score;
                        // Création du résultat
                        GameResult::create([
                            'game_question_id' => $gameQuestion->id,
                            'user_id' => $player->user_id,
                            'response' => $response,
                            'is_correct' => $isCorrect,
                            'score' => $score
                        ]);
                        $finalScore = $finalScore + $score;
                    }
                    // Définition d'un vrai résultat pour la partie du joueur
                    $gamePlayer = GamePlayer::where('id', $player->id)->first();
                    $gamePlayer->final_score = $finalScore;
                    $gamePlayer->save();
                }
                // Définition des places après toutes les réponses des joueurs
                $gameplayers = GamePlayer::where('game_id', $game->id)->orderBy('final_score', 'DESC')->get();
                $finalPlace = 1;
                // Parcours des joueurs classés par score
                foreach ($gameplayers as $gameplayer) {
                    // Attribution de la place
                    $gameplayer->final_place = $finalPlace;
                    $gameplayer->save();
                    $finalPlace++;
                }
            }
        }
    }
}
