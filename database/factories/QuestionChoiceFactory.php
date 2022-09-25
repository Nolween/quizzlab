<?php

namespace Database\Factories;


use App\Models\Question;
use App\Models\QuestionChoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QuestionChoice>
 */
class QuestionChoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $questionId = Question::inRandomOrder()->first()->id;
        // Y a-t-il déjà une bonne réponse pour la question ?
        $rightChoice = QuestionChoice::where('question_id', $questionId)->where('is_correct', true)->first();
        // Combien y a-t-il de choix déjà présent pour la question ?
        $choicesCount = QuestionChoice::where('question_id', $questionId)->get()->count();
        return [
            'question_id' => $questionId,
            'title' => fake()->sentence(3),
            // Si pas encore de réponse positive
            'is_correct' => !(!empty($rightChoice) && $choicesCount < 4) || fake()->boolean(30)
        ];
    }
}
