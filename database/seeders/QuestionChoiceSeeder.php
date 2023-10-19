<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\QuestionChoice;
use Illuminate\Database\Seeder;

class QuestionChoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = Question::all();
        // Parcours de toutes les questions
        foreach ($questions as $question) {
            // Quelle est la bonne réponse ?
            $rightChoice = rand(1, 4);
            for ($i = 1; $i <= 4; $i++) {
                QuestionChoice::create([
                    'question_id' => $question->id,
                    'title' => fake()->sentence(3),
                    'is_correct' => $rightChoice == $i,
                ]);
            }
        }
    }
}
