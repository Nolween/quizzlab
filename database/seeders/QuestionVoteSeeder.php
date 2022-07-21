<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\QuestionVote;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionVoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $totalUsers = User::all()->count();
        // Quelle est la valeur de vote pour une question? Pour arriver à 100 il faudrait qu'au moins un quart des utilisateurs mettent oui
        $votePower = ceil(100 / ($totalUsers / 4));
        // Si le pouvoir de vote selon le total d'utilisateur est inférieur à 1, on laisse 1
        $votePower = $votePower < 1 ? 1 : $votePower;
        // Parcours de toutes les questions existantes
        $questions = Question::all();
        foreach ($questions as $question) {
            // Nombre au hasard de vote entre 0 et le max d'utilisateurs
            $voteCount = rand(0, $totalUsers);
            for ($i = 1; $i <= $voteCount; $i++) {
                $randomUserId = User::inRandomOrder()->first()->id;
                // L'utilisateur a t-il déjà voté pour la question?
                $voted = QuestionVote::where('user_id', $randomUserId)->where('question_id', $question->id)->first();
                // Si la personne n'a pas encore voté pour la question
                if (!$voted) {
                    QuestionVote::create([
                        'user_id' => $randomUserId,
                        'question_id' => $question->id,
                        'has_approved' => fake()->boolean(75)
                    ]);
                }
            }

            // Modification du score de vote selon le nombre de positifs / négatifs
            $positiveVote = QuestionVote::where('question_id', $question->id)->where('has_approved', 1)->get()->count();
            $negativeVote = QuestionVote::where('question_id', $question->id)->where('has_approved', 0)->get()->count();
            $question->vote = ($votePower * $positiveVote) - ($votePower * $negativeVote);
            $question->is_integrated = $question->vote >= 100 ? true : false;
            $question->save();
        }
    }
}
