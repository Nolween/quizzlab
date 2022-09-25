<?php

namespace Database\Seeders;

use App\Models\GameRule;
use Illuminate\Database\Seeder;

class GameRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {

        $source = [['name' => 'Partie Classique'], ['name' => 'Survie']];

        foreach ($source as $item) {
            GameRule::factory()->create($item);
        }
    }
}
