<?php

namespace Database\Seeders;

use App\Models\QuestionComment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        QuestionComment::factory(150)->create();

        // On passe certains commentaires en réponse de commentaire
        $responseComments = QuestionComment::inRandomOrder()->take(50)->get();
        foreach ($responseComments as $responseComment) {
            // Attribution de l'id d'un autre commentaire
            $responseComment->comment_id = QuestionComment::where('id', '!=', $responseComment->id)->inRandomOrder()->first()->id;
            $responseComment->save();
        }
    }
}
