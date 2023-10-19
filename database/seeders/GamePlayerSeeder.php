<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\User;
use Illuminate\Database\Seeder;

class GamePlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Parcours de toutes les parties existantes
        $games = Game::all();
        foreach ($games as $game) {
            // Si la partie est en attente, on met moins de joueurs que le max, si la partie est lancée, le max.
            $playersCount = $game->has_begun == 0 ? rand(1, $game->max_players) : $game->max_players;
            $finalCore = fake()->randomFloat(2, 20, 50);
            // Tant qu'on n'a pas le nombre de joueurs défini
            for ($i = 1; $i <= $playersCount; $i++) {
                $playerScore = $finalCore - ($i + fake()->randomFloat(2, 0, 1));
                $playerScore = max($playerScore, 0);
                // Création d'un joueur dans la partie
                GamePlayer::create([
                    'game_id' => $game->id,
                    'user_id' => User::inRandomOrder()->first()->id,
                    'is_ready' => $game->has_begun ? 1 : rand(0, 1),
                    'final_score' => $game->has_begun ? $playerScore : 0,
                    'final_place' => $game->has_begun ? $i : null,
                ]);
            }
        }
    }
}
