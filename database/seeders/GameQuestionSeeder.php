<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\GameQuestion;
use App\Models\GameTag;
use App\Models\Question;
use App\Models\QuestionChoice;
use App\Models\QuestionTag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Seeder;

class GameQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Parcours de toutes les parties
        $games = Game::all();
        foreach ($games as $game) {
            $questionsIdsGame = [];
            // Attribution des questions selon le nombre de questions défini
            for ($i = 0; $i < $game->question_count; $i++) {
                // Quels sont tous les thèmes associés de la partie?
                $tagIds = GameTag::where('game_id', $game->id)->get()->pluck('tag_id');
                // Si la partie demande des questions qui ont tous les thèmes associés
                if ($game->questions_have_all_tags == true) {
                    // Quels sont les thèmes de la partie?
                    $gameTags = GameTag::where('game_id', $game->id)->get();
                    // On va créer une nouvelle question automatiquement modérée et intégrée
                    $newQuestion = Question::factory()->create([
                        'is_moderated' => true,
                        'is_integrated' => true
                    ]);
                    // On va attribuer à cette nouvelle question tous les tags de la partie
                    foreach ($gameTags as $gameTag) {
                        QuestionTag::create([
                            'question_id' => $newQuestion->id,
                            'tag_id' => $gameTag->tag_id
                        ]);
                    }
                    // Création des choix pour la question
                    // Quelle est la bonne réponse?
                    $rightChoice = rand(1, 4);
                    for ($j = 1; $j <= 4; $j++) {
                        QuestionChoice::create([
                            'question_id' => $newQuestion->id,
                            'title' => fake()->sentence(3),
                            'is_correct' => $rightChoice == $j ? true : false
                        ]);
                    }
                    // Attribution de la nouvelle question à la partie
                    GameQuestion::create([
                        'game_id' => $game->id,
                        'question_id' => $newQuestion->id,
                        'order' => $i
                    ]);
                    // On ajout l'id de la question au tableau pour ne pas la reprendre
                    $questionsIdsGame[] = $newQuestion->id;
                }
                // Si il faut une question qui possède au moins un tag de la partie
                else {
                    $question = Question::where('is_integrated', true)->whereHas('tags', function (Builder $query) use ($tagIds) {
                        $query->whereIn('tag_id', $tagIds);
                    })->whereNotIn('id', $questionsIdsGame)->inRandomOrder()->firstOr(function () use ($tagIds, $game, $i) {
                        $newQuestion = Question::where('is_integrated', true)->inRandomOrder()->first();
                        // Si on a pas de question avec au moins un thème approprié, on en créé un
                        QuestionTag::create([
                            'question_id' => $newQuestion->id,
                            'tag_id' => fake()->randomElement($tagIds)
                        ]);
                        GameQuestion::create([
                            'game_id' => $game->id,
                            'question_id' => $newQuestion->id,
                            'order' => $i
                        ]);
                        // On ajout l'id de la question au tableau pour ne pas la reprendre
                        $questionsIdsGame[] = $newQuestion->id;
                    });

                    // Si on a bien une question trouvée
                    if (!empty($question)) {
                        GameQuestion::create([
                            'game_id' => $game->id,
                            'question_id' => $question->id,
                            'order' => $i
                        ]);
                        // On ajout l'id de la question au tableau pour ne pas la reprendre
                        $questionsIdsGame[] = $question->id;
                    }
                }
            }
        }
    }
}
