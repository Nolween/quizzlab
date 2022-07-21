<?php

namespace Database\Seeders;

use App\Models\GameQuestion;
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
        GameQuestion::factory(150)->create();

        // Définition des ordres de questions
        $questionGames = GameQuestion::all();
        foreach ($questionGames as $questionGame) {
            $questionGame->order = GameQuestion::where('game_id', $questionGame->game_id)->orderBy('order', 'DESC')->first()->order + 1 ?? 1;
            $questionGame->save();
        }
    }
}
