<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GameChat>
 */
class GameChatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $gameId = Game::where('has_begun', true)->inRandomOrder()->first()->id;
        // Si la partie a déjà commencé, le joueur peut avoir discuté
        return [
            'game_id' => $gameId,
            'user_id' => User::inRandomOrder()->first()->id,
            'text' => fake()->sentence()
        ];
    }
}
