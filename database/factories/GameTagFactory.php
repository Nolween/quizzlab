<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GameTag>
 */
class GameTagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'game_id' => Game::inRandomOrder()->first()->id,
            'tag_id' => Tag::inRandomOrder()->first()->id,
        ];
    }
}
