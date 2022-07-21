<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\GameQuestion;
use App\Models\Question;
use App\Models\QuestionTag;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        
        // Création des paramètres
        $this->call(RoleSeeder::class);
        $this->call(TagSeeder::class);
        $this->call(GameRuleSeeder::class);
        
        // Création des jeux de test
        $this->call(UserSeeder::class);
        $this->call(QuestionSeeder::class);
        $this->call(QuestionTagSeeder::class);
        $this->call(GameSeeder::class);
        $this->call(GameTagSeeder::class);
        $this->call(GameQuestionSeeder::class);
        $this->call(GamePlayerSeeder::class);
        $this->call(GameChatSeeder::class);
        $this->call(GameResultSeeder::class);
    }
}
