<?php

namespace App\Observers;

use App\Models\Question;
use App\Services\ElasticService;

class QuestionObserver
{

    protected $elastic;

    public function __construct(ElasticService $elasticService) {
        $this->elastic = $elasticService;
    }


    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;

    /**
     * Handle the Question "created" event.
     * Ajout de la question dans Elasticsearch
     * 
     * @param  \App\Models\Question  $question
     * @return void
     */
    public function created(Question $question)
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
     * @param  \App\Models\Question  $question
     * @return void
     */
    public function updated(Question $question)
    {
        //
    }

    /**
     * Handle the Question "deleted" event.
     *
     * @param  \App\Models\Question  $question
     * @return void
     */
    public function deleted(Question $question)
    {
        //
    }

    /**
     * Handle the Question "restored" event.
     *
     * @param  \App\Models\Question  $question
     * @return void
     */
    public function restored(Question $question)
    {
        //
    }

    /**
     * Handle the Question "force deleted" event.
     *
     * @param  \App\Models\Question  $question
     * @return void
     */
    public function forceDeleted(Question $question)
    {
        //
    }
}
