<?php

namespace App\Http\Resources\Questions;

use Illuminate\Http\Resources\Json\JsonResource;

class QuestionSearchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Response  $response
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
            foreach ($arrayResponse['hits']['hits'] as $questionResponseK => $questionResponseV) {
                // Ajout dans le tableau de retour
                $finalResponse[] = ['question_id' => $questionResponseV['_source']['question_id'], 'question' => $questionResponseV['_source']['question']];
            }
        }

        return $finalResponse;
    }
}
