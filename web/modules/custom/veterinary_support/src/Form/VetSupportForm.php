<?php

namespace Drupal\veterinary_support\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Veterinary support contact form.
 */
class VetSupportForm extends FormBase {

  public function getFormId() {
    return 'vet_support_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['pet_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Pet Name'),
      '#required' => TRUE,
    ];
    $form['issue'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Describe the Issue'),
      '#required' => TRUE,
    ];
    $form['contact_info'] = [
      '#type' => 'email',
      '#title' => $this->t('Your Email'),
      '#required' => TRUE,
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];
    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Send email or integrate with AI/external service.
    // For now, just message.
    $this->messenger()->addMessage($this->t('Your request has been submitted. A veterinarian will contact you soon.'));
  }

}