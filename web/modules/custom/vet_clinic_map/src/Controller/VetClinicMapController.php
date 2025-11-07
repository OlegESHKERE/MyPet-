<?php

namespace Drupal\vet_clinic_map\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Drupal\node\Entity\Node;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller for Vet Clinic Map functionality.
 */
class VetClinicMapController extends ControllerBase {

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Constructs a VetClinicMapController object.
   *
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection.
   */
  public function __construct(Connection $database) {
    $this->database = $database;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database')
    );
  }

  /**
   * Displays the vet clinic finder page.
   *
   * @return array
   *   A render array for the clinic finder page.
   */
  public function clinicFinder() {
    $form = $this->formBuilder()->getForm('Drupal\vet_clinic_map\Form\VetClinicFinderForm');
    
    $build = [
      '#theme' => 'vet_clinic_finder',
      '#form' => $form,
      '#map_container' => [
        '#type' => 'html_tag',
        '#tag' => 'div',
        '#attributes' => [
          'id' => 'vet-clinic-map',
          'style' => 'height: 500px; width: 100%;',
        ],
      ],
      '#clinics_list' => [
        '#type' => 'html_tag',
        '#tag' => 'div',
        '#attributes' => [
          'id' => 'clinics-list',
          'class' => ['clinics-list-container'],
        ],
      ],
      '#attached' => [
        'library' => [
          'vet_clinic_map/vet-clinic-map',
          'vet_clinic_map/google-maps',
        ],
        'drupalSettings' => [
          'vetClinicMap' => [
            'apiKey' => $this->config('vet_clinic_map.settings')->get('google_maps_api_key') ?: '',
            'defaultRegion' => 'Київська Область',
            'defaultCity' => 'Київ',
          ],
        ],
      ],
    ];

    return $build;
  }

  /**
   * AJAX callback to get clinics for a specific region and city.
   *
   * @param string $region
   *   The region name.
   * @param string $city
   *   The city name.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The current request.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   JSON response with clinic data.
   */
  public function ajaxClinics($region, $city, Request $request) {
    $clinics = $this->getClinicsForLocation($region, $city);
    
    $clinic_data = [];
    foreach ($clinics as $clinic) {
      $clinic_data[] = [
        'id' => $clinic->nid,
        'title' => $clinic->title,
        'address' => $clinic->field_address_value ?? '',
        'phone' => $clinic->field_phone_value ?? '',
        'email' => $clinic->field_email_value ?? '',
        'latitude' => $clinic->field_latitude_value ?? null,
        'longitude' => $clinic->field_longitude_value ?? null,
        'region' => $clinic->field_select_oblast_value ?? '',
        'city' => $clinic->field_city_select_value ?? '',
        'url' => '/node/' . $clinic->nid,
      ];
    }

    return new JsonResponse([
      'status' => 'success',
      'clinics' => $clinic_data,
      'count' => count($clinic_data),
      'region' => $region,
      'city' => $city,
    ]);
  }

  /**
   * Get clinics for a specific location.
   *
   * @param string $region
   *   The region name.
   * @param string $city
   *   The city name.
   *
   * @return array
   *   Array of clinic objects.
   */
  protected function getClinicsForLocation($region, $city) {
    $query = $this->database->select('node_field_data', 'n');
    $query->fields('n', ['nid', 'title']);
    $query->condition('n.status', 1);
    $query->condition('n.type', 'vet_clinic'); // Adjust content type as needed
    
    // Join with region field
    $query->leftJoin('node__field_select_oblast', 'region', 'n.nid = region.entity_id');
    $query->addField('region', 'field_select_oblast_value');
    
    // Join with city field
    $query->leftJoin('node__field_city_select', 'city', 'n.nid = city.entity_id');
    $query->addField('city', 'field_city_select_value');
    
    // Join with other important fields
    $query->leftJoin('node__field_address', 'addr', 'n.nid = addr.entity_id');
    $query->addField('addr', 'field_address_value');
    
    $query->leftJoin('node__field_phone', 'phone', 'n.nid = phone.entity_id');
    $query->addField('phone', 'field_phone_value');
    
    $query->leftJoin('node__field_email', 'email', 'n.nid = email.entity_id');
    $query->addField('email', 'field_email_value');
    
    $query->leftJoin('node__field_latitude', 'lat', 'n.nid = lat.entity_id');
    $query->addField('lat', 'field_latitude_value');
    
    $query->leftJoin('node__field_longitude', 'lng', 'n.nid = lng.entity_id');
    $query->addField('lng', 'field_longitude_value');
    
    // Filter by region and city
    if ($region && $region !== 'All') {
      $query->condition('region.field_select_oblast_value', $region);
    }
    
    if ($city && $city !== 'All') {
      $query->condition('city.field_city_select_value', $city);
    }
    
    $query->orderBy('n.title', 'ASC');
    
    return $query->execute()->fetchAll();
  }

}
