<?php

namespace App\Observers;

use App\Models\Tag;
use App\Services\ElasticService;

class TagObserver
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
     * Handle the Tag "created" event.
     * Ajout du tag dans Elasticsearch
     *
     * @param Tag $tag
     * @return void
     */
    public function created(Tag $tag): void
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
     * @param Tag $tag
     * @return void
     */
    public function updated(Tag $tag): void
    {
        //
    }

    /**
     * Handle the Tag "deleted" event.
     *
     * @param Tag $tag
     * @return void
     */
    public function deleted(Tag $tag): void
    {
        //
    }

    /**
     * Handle the Tag "restored" event.
     *
     * @param Tag $tag
     * @return void
     */
    public function restored(Tag $tag): void
    {
        //
    }

    /**
     * Handle the Tag "force deleted" event.
     *
     * @param Tag $tag
     * @return void
     */
    public function forceDeleted(Tag $tag): void
    {
        //
    }
}
