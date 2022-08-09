<?php

namespace App\Http\Resources\Tags;

use Illuminate\Http\Resources\Json\JsonResource;

class TagSearchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($response)
    {
        // On transforme ce qu'on a reçu d'Elsatic en tableau
        $arrayResponse = parent::toArray($response);

        // Déclaration du tableau final de retour
        $finalResponse = [];
        // Si on a bien des résultats
        if (isset($arrayResponse['hits']['hits'])) {
            // Parcours des résultats
            foreach ($arrayResponse['hits']['hits'] as $tagResponseK => $tagResponseV) {
                // Ajout dans le tableau de retour
                $finalResponse[] = ['tag_id' => $tagResponseV['_source']['tag_id'], 'tag' => $tagResponseV['_source']['tag']];
            }
        }

        return $finalResponse;
    }
}
