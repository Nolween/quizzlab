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
   * Récupération d'un certain nombre d'entrées d'un index avec le query match
   *
   * @param string $index Index / Table à récupérer
   * @param int $results Nombre de résultats à récupérer
   * @param string $search Valeur que l'on recherche
   * @param string $field Le champ que l'on recherche
   * @return Response Réponse entière (avec ->body(), ->status(),...)
   */
  public function getMatchFromIndex(string $index, int $results = null, string $field = null, string $search = null)
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

  /**
   * Récupération d'un certain nombre d'entrées d'un index avec les wildcards
   *
   * @param string $index Index / Table à récupérer
   * @param int $results Nombre de résultats à récupérer
   * @param string $search Valeur que l'on recherche
   * @param string $field Le champ que l'on recherche
   * @return Response Réponse entière (avec ->body(), ->status(),...)
   */
  public function getWildcardFromIndex(string $index, int $results = null, string $field = null, string $search = null)
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
        "wildcard" => [
          $field => "*$search*"
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

  /**
   * Envoi d'une entrée à ajouter dans Elasticsearch 
   *
   * @param string $index Index / Table à récupérer
   * @param array $data Tableau avec les clés/valeurs à envoyer
   * @return Response Réponse entière (avec ->body(), ->status(),...)
   */
  public function insertInIndex(string $index, array $data = [])
  {
    // Paramètres à envoyer
    $params = ['pretty' => ""];
    // Corps de la requête, un tableau de clés / valeurs
    $body = $data;
    // Encodage JSON du body
    $json = json_encode($body);

    $response = Http::accept('application/json')
      ->withBasicAuth($this->elasticUser, $this->elasticPassword)
      ->withBody($json, 'application/json')
      ->post("$this->elasticHost:$this->elasticPort/$index/_doc/", $params);

    return $response;
  }

  /**
   * Création d'un index dans Elasticsearch 
   *
   * @param string $index Index / Table à créer
   * @param array $data Tableau avec les clés/valeurs à envoyer
   * @return Response Réponse entière (avec ->body(), ->status(),...)
   */
  public function insertNewIndex(string $index, array $data = [])
  {
    // Paramètres à envoyer
    $params = ['pretty' => ""];
    // L'index existe t-il déjà?
    $existsResponse = Http::accept('application/json')
      ->withBasicAuth($this->elasticUser, $this->elasticPassword)
      ->head("$this->elasticHost:$this->elasticPort/$index/", $params);

    // Si on a une 404 parce que l'index n'existe pas
    if ($existsResponse->status() == 404) {

      // Corps de la requête, un tableau de champs / type avec 
      $body = ['mappings' => ['properties' => $data]];
      // Encodage JSON du body
      $json = json_encode($body);
      // Création de l'index
      $creationResponse = Http::accept('application/json')
        ->withBasicAuth($this->elasticUser, $this->elasticPassword)
        ->withBody($json, 'application/json')
        ->put("$this->elasticHost:$this->elasticPort/$index",  $params);

      return $creationResponse;
    }
  }

  /**
   * Suppression d'un index dans Elasticsearch 
   *
   * @param string $index Index / Table à supprimer
   * @return Response Réponse entière (avec ->body(), ->status(),...)
   */
  public function deleteIndex(string $index)
  {
    // Paramètres à envoyer
    $params = ['pretty' => ""];

    // L'index existe t-il déjà?
    $existResponse = Http::accept('application/json')
      ->withBasicAuth($this->elasticUser, $this->elasticPassword)
      ->head("$this->elasticHost:$this->elasticPort/$index/", $params);

    // Si on a une 404 parce que l'index n'existe pas
    if ($existResponse->ok()) {
      $response = Http::accept('application/json')
        ->withBasicAuth($this->elasticUser, $this->elasticPassword)
        ->delete("$this->elasticHost:$this->elasticPort/$index");

      return $response;
    }
  }

  /**
   * Test de la connexion
   *
   * @return Response Réponse entière (avec ->body(), ->status(),...)
   */
  public function testConnexion()
  {
    // Paramètres à envoyer
    $params = ['pretty' => ""];

    // L'index existe t-il déjà?
    $response = Http::accept('application/json')
      ->withBasicAuth($this->elasticUser, $this->elasticPassword)
      ->get("$this->elasticHost:$this->elasticPort/", $params);

    return $response;
  }
}
