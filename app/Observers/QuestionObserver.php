<?php

namespace App\Observers;

use App\Models\Question;
use App\Services\ElasticService;

class QuestionObserver
{
    protected ElasticService $elastic;

    /**
     * Handle events after all transactions are committed.
     */
    public bool $afterCommit = true;

    public function __construct(ElasticService $elasticService)
    {
        $this->elastic = $elasticService;
    }

    /**
     * Handle the Question "created" event.
     * Ajout de la question dans Elasticsearch
     */
    public function created(Question $question): void
    {
        // Ne pas intéragir avec ES en environnement de test
        if (!app()->environment('testing')) {
            // Préparation des paramètres pour l'API
            $dataToSend = [
                'question'    => $question->question,
                'question_id' => $question->id,
            ];
            $this->elastic->insertInIndex('questions', $dataToSend);
        }
    }

    /**
     * Handle the Question "updated" event.
     */
    public function updated(Question $question): void
    {
        //
    }

    /**
     * Handle the Question "deleted" event.
     */
    public function deleted(Question $question): void
    {
        //
    }

    /**
     * Handle the Question "restored" event.
     */
    public function restored(Question $question): void
    {
        //
    }

    /**
     * Handle the Question "force deleted" event.
     */
    public function forceDeleted(Question $question): void
    {
        //
    }
}
