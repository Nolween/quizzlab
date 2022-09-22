<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\GameQuestion;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GameQuestion>
 */
class GameQuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $gameId = Game::inRandomOrder()->first()->id;
        // Quel est l'ordre de la dernière question?
        $questionCount = GameQuestion::where('game_id', $gameId)->orderBy('order', 'DESC')->first();
        $order = ($questionCount !== null) ? $questionCount->order + 1 : 0;
        return [
            'game_id' => $gameId,
            'question_id' => Question::where('is_integrated', true)->inRandomOrder()->first()->id,
            'order' => $order
        ];
    }
}
