<?php

namespace Database\Seeders;

use App\Models\QuestionTag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        QuestionTag::factory(100)->create();

        // On retire les doublons de tag sur les questions
        $questionTags = QuestionTag::all();
        foreach ($questionTags as $questionTag) {
            $sameQuestionTags = QuestionTag::where('question_id', $questionTag->question_id)->where('tag_id', $questionTag->tag_id)->where('id', '!=', $questionTag->id)->get();
            foreach ($sameQuestionTags as $sameQuestionTag) {
                $sameQuestionTag->delete();
            }
        }

    }
}
