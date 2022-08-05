<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class ElasticService
{

  public $elasticHost;
  public $elasticPort;
  public $elasticUser;
  public $elasticPassword;

  /**
   * Définition des propriétés de l'instance à sa création
   *
   * @param string $elasticHost Adresse du serveur Elastic
   * @param string $elasticPort Port du serveur Elastic
   * @param string $elasticUser Username pour l'authentification
   * @param string $elasticPassword Mot de passe pour l'authentification
   */
  public function __construct(string $elasticHost, string $elasticPort, string $elasticUser, string $elasticPassword)
  {
    $this->elasticHost = $elasticHost;
    $this->elasticPort = $elasticPort;
    $this->elasticUser = $elasticUser;
    $this->elasticPassword = $elasticPassword;
  }

  /**
   * Récupération d'un certain nombre d'entrées d'un index
   *
   * @param string $index Index / Table à récupérer
   * @param int $results Nombre de résultats à récupérer
   * @param string $search Valeur que l'on recherche
   * @param string $field Le champ que l'on recherche
   * @return Response Réponse entière (avec ->body(), ->status(),...)
   */
  public function getFromIndex(string $index, int $results = null, string $field = null, string $search = null)
  {
    // Paramètres à envoyer
    $params = ['pretty' => ""];
    // Corps de la requête
    $body = [];
    // Nombre de résultats limité
    if ($results > 0) {
      $body['from'] = 0;
      $body['size'] = $results;
    }
    // Si on a une recherche de champ
    if (!empty($field) && !empty($search)) {
      // On cherche pour le champ indiqué la valeur de recherche
      $body["query"] = [
        "match" => [
          $field => [ // Champ dans lequel on recherche
            "query" => $search,
            "fuzziness" => "AUTO" // tolérance aux fautes
          ]
        ]
      ];
    }
    // Encodage JSON du body
    $json = json_encode($body);

    $response = Http::accept('application/json')
      ->withBasicAuth($this->elasticUser, $this->elasticPassword)
      ->withBody($json, 'application/json')
      ->get("$this->elasticHost:$this->elasticPort/$index/_search/", $params);

    return $response;
  }
}
