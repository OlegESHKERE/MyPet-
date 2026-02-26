<?php

namespace Drupal\pet_api\Service;

use GuzzleHttp\Client;

/**
 * Service for external API integrations.
 */
class ExternalApiService {

  protected $httpClient;

  public function __construct(Client $http_client) {
    $this->httpClient = $http_client;
  }

  /**
   * Fetch feed ratings from external API.
   */
  public function getFeedRatings($feed_name) {
    // Example: Integrate with Open Food Facts or similar.
    try {
      $response = $this->httpClient->get("https://world.openfoodfacts.org/cgi/search.pl?search_terms=$feed_name&json=1");
      $data = json_decode($response->getBody(), TRUE);
      return $data['products'] ?? [];
    } catch (\Exception $e) {
      \Drupal::logger('pet_api')->error('Error fetching feed ratings: ' . $e->getMessage());
      return [];
    }
  }

}