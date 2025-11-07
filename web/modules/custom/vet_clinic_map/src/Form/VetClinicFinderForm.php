<?php

namespace Drupal\vet_clinic_map\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;

/**
 * Provides a form for selecting region and city to find vet clinics.
 */
class VetClinicFinderForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'vet_clinic_finder_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    
    $form['#attributes']['class'][] = 'vet-clinic-finder-form';
    $form['#attributes']['id'] = 'vet-clinic-finder-form';
    
    // Region selection
    $form['region'] = [
      '#type' => 'select',
      '#title' => $this->t('Select Region'),
      '#options' => $this->getRegionOptions(),
      '#default_value' => '',
      '#empty_option' => $this->t('Any Region'),
      '#ajax' => [
        'callback' => '::updateCityOptions',
        'wrapper' => 'city-wrapper',
        'event' => 'change',
      ],
      '#attributes' => [
        'class' => ['region-select'],
      ],
    ];

    // City selection (will be updated via AJAX)
    $form['city_wrapper'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'city-wrapper'],
    ];
    
    $selected_region = $form_state->getValue('region');
    $city_options = $this->getCityOptions($selected_region);
    
    $form['city_wrapper']['city'] = [
      '#type' => 'select',
      '#title' => $this->t('Select City'),
      '#options' => $city_options,
      '#default_value' => '',
      '#empty_option' => $this->t('Any City'),
      '#attributes' => [
        'class' => ['city-select'],
      ],
    ];

    // Search button
    $form['actions'] = [
      '#type' => 'actions',
    ];
    
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Find Clinics'),
      '#attributes' => [
        'class' => ['btn', 'btn-primary', 'find-clinics-btn'],
      ],
    ];

    return $form;
  }

  /**
   * AJAX callback to update city options based on selected region.
   */
  public function updateCityOptions(array &$form, FormStateInterface $form_state) {
    return $form['city_wrapper'];
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $region = $form_state->getValue('region');
    $city = $form_state->getValue('city');
    
    // This will be handled by JavaScript - no need for server-side redirect
    // The JavaScript will make an AJAX call to load the clinics
  }

  /**
   * Get available region options.
   *
   * @return array
   *   Array of region options.
   */
  protected function getRegionOptions() {
    // This should ideally come from your field configuration or database
    // For now, using common Ukrainian regions
    return [
      'Київська Область' => $this->t('Kyiv Region'),
      'Львівська Область' => $this->t('Lviv Region'),
      'Харківська Область' => $this->t('Kharkiv Region'),
      'Одеська Область' => $this->t('Odesa Region'),
      'Дніпропетровська Область' => $this->t('Dnipropetrovsk Region'),
      'Запорізька Область' => $this->t('Zaporizhzhia Region'),
      'Донецька Область' => $this->t('Donetsk Region'),
      'Полтавська Область' => $this->t('Poltava Region'),
      'Черкаська Область' => $this->t('Cherkasy Region'),
      'Вінницька Область' => $this->t('Vinnytsia Region'),
      'Житомирська Область' => $this->t('Zhytomyr Region'),
      'Закарпатська Область' => $this->t('Zakarpattia Region'),
      'Івано-Франківська Область' => $this->t('Ivano-Frankivsk Region'),
      'Кіровоградська Область' => $this->t('Kirovohrad Region'),
      'Луганська Область' => $this->t('Luhansk Region'),
      'Миколаївська Область' => $this->t('Mykolaiv Region'),
      'Рівненська Область' => $this->t('Rivne Region'),
      'Сумська Область' => $this->t('Sumy Region'),
      'Тернопільська Область' => $this->t('Ternopil Region'),
      'Хмельницька Область' => $this->t('Khmelnytskyi Region'),
      'Черкаська Область' => $this->t('Cherkasy Region'),
      'Чернігівська Область' => $this->t('Chernihiv Region'),
      'Чернівецька Область' => $this->t('Chernivtsi Region'),
      'Херсонська Область' => $this->t('Kherson Region'),
    ];
  }

  /**
   * Get city options based on selected region.
   *
   * @param string $region
   *   The selected region.
   *
   * @return array
   *   Array of city options.
   */
  protected function getCityOptions($region = '') {
    // This should ideally query your database for cities in the selected region
    // For now, providing a basic structure
    
    $cities = [];
    
    switch ($region) {
      case 'Київська Область':
        $cities = [
          'Київ' => $this->t('Kyiv'),
          'Біла Церква' => $this->t('Bila Tserkva'),
          'Бровари' => $this->t('Brovary'),
          'Ірпінь' => $this->t('Irpin'),
          'Буча' => $this->t('Bucha'),
        ];
        break;
        
      case 'Львівська Область':
        $cities = [
          'Львів' => $this->t('Lviv'),
          'Дрогобич' => $this->t('Drohobych'),
          'Червоноград' => $this->t('Chervonohrad'),
          'Стрий' => $this->t('Stryi'),
        ];
        break;
        
      case 'Харківська Область':
        $cities = [
          'Харків' => $this->t('Kharkiv'),
          'Лозова' => $this->t('Lozova'),
          'Ізюм' => $this->t('Izium'),
        ];
        break;
        
      default:
        // If no region selected or region not found, return empty array
        $cities = [];
        break;
    }
    
    return $cities;
  }

}
