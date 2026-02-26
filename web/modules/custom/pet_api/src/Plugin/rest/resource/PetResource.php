<?php

namespace Drupal\pet_api\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;

/**
 * Provides a resource for pet data.
 *
 * @RestResource(
 *   id = "pet_api_resource",
 *   label = @Translation("Pet API"),
 *   uri_paths = {
 *     "canonical" = "/api/pets"
 *   }
 * )
 */
class PetResource extends ResourceBase {

  /**
   * Responds to GET requests.
   */
  public function get() {
    $query = \Drupal::entityQuery('node')
      ->condition('type', 'pet');
    $nids = $query->execute();
    $pets = [];
    foreach ($nids as $nid) {
      $node = \Drupal\node\Entity\Node::load($nid);
      $pets[] = [
        'id' => $node->id(),
        'name' => $node->getTitle(),
        'breed' => $node->get('field_pet_breed')->value ?? '',
        'age' => $node->get('field_pet_age')->value ?? '',
      ];
    }
    return new ResourceResponse($pets);
  }

}