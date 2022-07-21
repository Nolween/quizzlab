<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\GameQuestion;
use App\Models\Question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        foreach($games as $game) {
            // Attribution des questions selon le nombre de questions défini
            for ($i=1; $i <= $game->question_count; $i++) {
                GameQuestion::create([
                    'game_id' => $game->id,
                    'question_id' => Question::inRandomOrder()->first()->id,
                    'order' => $i
                ]);
            } 
        } 

    }
}
