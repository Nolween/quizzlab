<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => rand(1,20),
            'question' => fake()->unique()->sentence(10),
            'answer' => fake()->sentence(),
            'image' => rand(1,40) . '.jpg',
            'is_integrated' => rand(0, 1),
            'vote' => rand(-500, 2000),
            'ratio_score' => fake()->randomFloat(2, 0, 1),
        ];
    }
}
