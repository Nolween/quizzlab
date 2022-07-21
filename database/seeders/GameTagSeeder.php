<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\GameTag;
use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GameTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $games = Game::all();
        // Parcours de toutes les partie
        foreach ($games as $game) {
            // Combien de tag pour la game?
            $tagCount = rand(2, 25);
            $notIn = [];
            for ($i = 1; $i <= $tagCount; $i++) {
                $newGameTag = GameTag::create([
                    'game_id' => $game->id,
                    'tag_id' => Tag::whereNotIn('id', $notIn)->inRandomOrder()->first()->id,
                ]);
                // On rajoute ce tag au tableau pour ne pas l'avoir en doublon
                $notIn[] = $newGameTag->tag_id;
            }
        }

    }
}
