<?php

namespace Database\Factories;

use App\Models\GamePlayer;
use App\Models\GameQuestion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GameResult>
 */
class GameResultFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $gameQuestion = GameQuestion::where('has_begun', true)->inRandomOrder()->first();
        $isCorrect = rand(0,1);
        $response = $isCorrect == 1 ? $gameQuestion->question->answer : fake()->sentence(1);
        $score = $gameQuestion->question->ratio_score;
        return [
            'game_question_id' => $gameQuestion->id,
            'user_id' => GamePlayer::where('game_id', $gameQuestion->id)->inRandomOrder()->first()->id,
            'response' => $response,
            'is_correct' => $isCorrect,
            'score' => $score
        ];
    }
}
