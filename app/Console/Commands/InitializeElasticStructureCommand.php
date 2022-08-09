<?php

namespace App\Console\Commands;

use App\Services\ElasticService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class InitializeElasticStructureCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elastic:initialize-structure';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '(Ré)Initialise les index de la partie Elastic';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(ElasticService $elasticService)
    {
        // Elastic est-il bien connecté?
        $reponse = $elasticService->testConnexion();
        if ($reponse->ok()) {
            // Suppression des index si déjà existant
            $elasticService->deleteIndex('questions');
            $elasticService->deleteIndex('tags');
            // Ajout des champs dans les index
            $elasticService->insertNewIndex('questions', ['question' => ['type' => 'text'], 'question_id' => ['type' => 'integer']]);
            $elasticService->insertNewIndex('tags', ['tag' => ['type' => 'text'], 'tag_id' => ['type' => 'integer']]);
            $this->info("Structure Elastic initialisée !");
            return true;
        } else {
            $message = "Erreur à la connexion d'Elastic. Avez-vous démarré le serveur?";
            $this->info($message);
            return $message;
        }
    }
}
