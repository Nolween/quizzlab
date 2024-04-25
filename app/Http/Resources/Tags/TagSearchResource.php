<?php

namespace App\Http\Resources\Tags;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class TagSearchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($response): array|JsonSerializable|Arrayable
    {
        // On transforme ce qu'on a reçu d'Elastic en tableau
        $arrayResponse = parent::toArray($response);

        // Déclaration du tableau final de retour
        $finalResponse = [];
        // Si on a bien des résultats
        if (isset($arrayResponse['hits']['hits'])) {
            // Parcours des résultats
            foreach ($arrayResponse['hits']['hits'] as $tagResponseV) {
                // Ajout dans le tableau de retour
                $finalResponse[] = ['tag_id' => $tagResponseV['_source']['tag_id'], 'tag' => $tagResponseV['_source']['tag']];
            }
        }

        return $finalResponse;
    }
}
