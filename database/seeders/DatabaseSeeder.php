<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\CommentApproval;
use App\Models\GameQuestion;
use App\Models\Question;
use App\Models\QuestionTag;
use App\Services\ElasticService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(ElasticService $elasticService)
    {
        

        //? Partie Elasticsearch
        // Initialisation via le lancement de la commande
        Artisan::call('elastic:initialize-structure');
        
        //? PARTIE SQL
        // Création des paramètres
        $this->call(RoleSeeder::class);
        $this->call(TagSeeder::class);
        $this->call(GameRuleSeeder::class);

        // Création des jeux de test
        $this->call(UserSeeder::class);
        $this->call(QuestionSeeder::class);
        $this->call(QuestionChoiceSeeder::class);
        $this->call(QuestionTagSeeder::class);
        $this->call(GameSeeder::class);
        $this->call(GameTagSeeder::class);
        $this->call(GameQuestionSeeder::class);
        $this->call(GamePlayerSeeder::class);
        $this->call(GameChatSeeder::class);
        $this->call(GameResultSeeder::class);
        $this->call(QuestionVoteSeeder::class);
        $this->call(QuestionCommentSeeder::class);
        $this->call(CommentApprovalSeeder::class);
    }
}
