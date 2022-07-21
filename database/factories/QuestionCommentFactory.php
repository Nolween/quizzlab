<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuestionComment>
 */
class QuestionCommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $randomSentencesCount = rand(1,5);
        $approvals = rand(0, User::all()->count());
        $disapprovals = rand(0, User::all()->count() - $approvals);
        return [
            'question_id' => Question::inRandomOrder()->first()->id,
            'user_id' => User::inRandomOrder()->first()->id,
            'comment' => fake()->sentences($randomSentencesCount, true),
            'disapprovals' => $approvals,
            'approvals' => $disapprovals,
            'comment_id' => null,
        ];
    }
}
