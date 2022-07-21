<?php

namespace Database\Factories;

use App\Models\QuestionComment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CommentApproval>
 */
class CommentApprovalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $userId = User::inRandomOrder()->first()->id;
        return [
            'comment_id' => QuestionComment::where('user_id', '!=', $userId)->inRandomOrder()->first()->id,
            'user_id' => $userId,
            'has_approved' => fake()->boolean(70)
        ];
    }
}
