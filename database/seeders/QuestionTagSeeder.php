<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\QuestionTag;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class QuestionTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $questions = Question::all();
        // Parcours de toutes les questions
        foreach ($questions as $question) {
            // Combien de tag pour la question ?
            $tagCount = rand(2, 5);
            $notIn = [];
            for ($i = 1; $i <= $tagCount; $i++) {
                $newQuestionTag = QuestionTag::create([
                    'question_id' => $question->id,
                    'tag_id' => Tag::whereNotIn('id', $notIn)->inRandomOrder()->first()->id,
                ]);
                // On rajoute ce tag au tableau pour ne pas l'avoir en doublon
                $notIn[] = $newQuestionTag->tag_id;
            }
        }
    }
}
