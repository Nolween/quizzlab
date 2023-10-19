<?php

namespace App\Http\Resources\Questions;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use JsonSerializable;

class QuestionSearchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Response  $response
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
            foreach ($arrayResponse['hits']['hits'] as $questionResponseV) {
                // Ajout dans le tableau de retour
                $finalResponse[] = ['question_id' => $questionResponseV['_source']['question_id'], 'question' => $questionResponseV['_source']['question']];
            }
        }

        return $finalResponse;
    }
}
