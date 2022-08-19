<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuestionVote>
 */
class QuestionVoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $userId = User::inRandomOrder()->first()->id;
        // On sélectionne une question dont la modération a été faite
        $questionId = Question::where('user_id', '!=', $userId)->where('is_moderated', true)->inRandomOrder()->first()->id;
        return [
            'user_id' => $userId,
            'question_id' => $questionId,
            'has_approved' => rand(0, 1)
        ];

    }
}
