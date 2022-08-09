<?php

namespace App\Observers;

use App\Models\Tag;
use App\Services\ElasticService;

class TagObserver
{


    protected $elastic;
    
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;

    public function __construct(ElasticService $elasticService) {
        $this->elastic = $elasticService;
    }



    /**
     * Handle the Tag "created" event.
     * Ajout du tag dans Elasticsearch
     *
     * @param  \App\Models\Tag  $tag
     * @return void
     */
    public function created(Tag $tag)
    {
        // Préparation des paramètres pour l'API
        $dataToSend = [
            'tag' => $tag->name,
            'tag_id' => $tag->id,
        ];
        $this->elastic->insertInIndex('tags', $dataToSend);
    }

    /**
     * Handle the Tag "updated" event.
     *
     * @param  \App\Models\Tag  $tag
     * @return void
     */
    public function updated(Tag $tag)
    {
        //
    }

    /**
     * Handle the Tag "deleted" event.
     *
     * @param  \App\Models\Tag  $tag
     * @return void
     */
    public function deleted(Tag $tag)
    {
        //
    }

    /**
     * Handle the Tag "restored" event.
     *
     * @param  \App\Models\Tag  $tag
     * @return void
     */
    public function restored(Tag $tag)
    {
        //
    }

    /**
     * Handle the Tag "force deleted" event.
     *
     * @param  \App\Models\Tag  $tag
     * @return void
     */
    public function forceDeleted(Tag $tag)
    {
        //
    }
}
