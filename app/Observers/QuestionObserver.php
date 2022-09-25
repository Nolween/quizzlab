<?php

namespace App\Observers;

use App\Models\Question;
use App\Services\ElasticService;

class QuestionObserver
{

    protected ElasticService $elastic;

    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public bool $afterCommit = true;

    public function __construct(ElasticService $elasticService) {
        $this->elastic = $elasticService;
    }


    /**
     * Handle the Question "created" event.
     * Ajout de la question dans Elasticsearch
     *
     * @param Question $question
     * @return void
     */
    public function created(Question $question): void
    {
        // Préparation des paramètres pour l'API
        $dataToSend = [
            'question' => $question->question,
            'question_id' => $question->id,
        ];
        $this->elastic->insertInIndex('questions', $dataToSend);
    }

    /**
     * Handle the Question "updated" event.
     *
     * @param Question $question
     * @return void
     */
    public function updated(Question $question): void
    {
        //
    }

    /**
     * Handle the Question "deleted" event.
     *
     * @param Question $question
     * @return void
     */
    public function deleted(Question $question): void
    {
        //
    }

    /**
     * Handle the Question "restored" event.
     *
     * @param Question $question
     * @return void
     */
    public function restored(Question $question): void
    {
        //
    }

    /**
     * Handle the Question "force deleted" event.
     *
     * @param Question $question
     * @return void
     */
    public function forceDeleted(Question $question): void
    {
        //
    }
}
