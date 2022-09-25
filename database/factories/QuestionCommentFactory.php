<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\QuestionComment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QuestionComment>
 */
class QuestionCommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $randomSentencesCount = rand(1,5);
        return [
            'question_id' => Question::where('is_moderated', true)->inRandomOrder()->first()->id,
            'user_id' => User::inRandomOrder()->first()->id,
            'comment' => fake()->sentences($randomSentencesCount, true),
            'disapprovals_count' => 0,
            'approvals_count' => 0,
            'comment_id' => null,
        ];
    }
}
