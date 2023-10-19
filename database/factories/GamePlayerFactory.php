<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GamePlayer>
 */
class GamePlayerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gameId = Game::inRandomOrder()->first()->id;

        // Si la partie a déjà commencé, le joueur a été prêt
        return [
            'game_id' => $gameId,
            'user_id' => User::inRandomOrder()->first()->id,
            'is_ready' => $gameId->has_begun ? 1 : rand(0, 1),
            'final_score' => fake()->randomFloat(),
            'final_place' => null,
        ];
    }
}
