<?php

namespace Database\Factories;

use App\Models\GameRule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GameRule>
 */
class GameRuleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name()
        ];
    }
}
