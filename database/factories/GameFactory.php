<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\GameRule;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Game>
 */
class GameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $questionCount = rand(5, 15);
        $hasBegun = fake()->boolean(90);
        // Si la partie a commencé
        $isFinished = $hasBegun == 1 ? fake()->boolean(90) : 0;
        if ($isFinished == 1) {
            $questionStep = $questionCount;
        } elseif ($hasBegun == 1 && $isFinished == 0) {
            $questionStep = rand(1, $questionCount);
        } else {
            $questionStep = null;
        }
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'game_rule_id' => GameRule::inRandomOrder()->first()->id,
            'max_players' => rand(1, 20),
            'response_time' => rand(10, 20),
            'question_count' => $questionCount,
            'questions_have_all_tags' => fake()->boolean(),
            'has_begun' => $hasBegun,
            'is_finished' => $isFinished,
            'game_code' => fake()->unique()->isbn13(),
            'question_step' => $questionStep
        ];
    }
}
