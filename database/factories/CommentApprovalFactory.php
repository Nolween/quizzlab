<?php

namespace Database\Factories;

use App\Models\CommentApproval;
use App\Models\QuestionComment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CommentApproval>
 */
class CommentApprovalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userId = User::inRandomOrder()->first()->id;
        return [
            'comment_id' => QuestionComment::where('user_id', '!=', $userId)->inRandomOrder()->first()->id,
            'user_id' => $userId,
            'has_approved' => fake()->boolean(70)
        ];
    }
}
