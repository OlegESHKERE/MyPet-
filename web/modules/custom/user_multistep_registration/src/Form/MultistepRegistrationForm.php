<?php

namespace Drupal\user_multistep_registration\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Multistep user registration form.
 */
class MultistepRegistrationForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'multistep_registration_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $step = $form_state->get('step') ?? 1;

    switch ($step) {
      case 1:
        $form = $this->buildStep1($form, $form_state);
        break;
      case 2:
        $form = $this->buildStep2($form, $form_state);
        break;
      case 3:
        $form = $this->buildStep3($form, $form_state);
        break;
    }

    return $form;
  }

  /**
   * Build step 1: Basic info.
   */
  private function buildStep1(array $form, FormStateInterface $form_state) {
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Username'),
      '#required' => TRUE,
    ];
    $form['mail'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
      '#required' => TRUE,
    ];
    $form['next'] = [
      '#type' => 'submit',
      '#value' => $this->t('Next'),
      '#submit' => ['::nextStep'],
    ];
    return $form;
  }

  /**
   * Build step 2: Additional info.
   */
  private function buildStep2(array $form, FormStateInterface $form_state) {
    $form['first_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('First Name'),
    ];
    $form['last_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Last Name'),
    ];
    $form['back'] = [
      '#type' => 'submit',
      '#value' => $this->t('Back'),
      '#submit' => ['::previousStep'],
    ];
    $form['next'] = [
      '#type' => 'submit',
      '#value' => $this->t('Next'),
      '#submit' => ['::nextStep'],
    ];
    return $form;
  }

  /**
   * Build step 3: Pet info.
   */
  private function buildStep3(array $form, FormStateInterface $form_state) {
    $form['pet_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Pet Name'),
    ];
    $form['pet_breed'] = [
      '#type' => 'select',
      '#title' => $this->t('Pet Breed'),
      '#options' => [
        'dog' => 'Dog',
        'cat' => 'Cat',
      ],
    ];
    $form['back'] = [
      '#type' => 'submit',
      '#value' => $this->t('Back'),
      '#submit' => ['::previousStep'],
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Register'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Create the user.
    $user = \Drupal\user\Entity\User::create([
      'name' => $form_state->getValue('name'),
      'mail' => $form_state->getValue('mail'),
      'pass' => 'temporary', // Set a temporary password or handle properly.
      'status' => 1,
    ]);
    $user->save();

    // Save additional data, perhaps to user fields or profile.
    // For pet info, create a pet node or save to user.

    $this->messenger()->addMessage($this->t('Registration complete.'));
  }

  /**
   * Next step submit handler.
   */
  public function nextStep(array &$form, FormStateInterface $form_state) {
    $step = $form_state->get('step') ?? 1;
    $form_state->set('step', $step + 1);
    $form_state->setRebuild();
  }

  /**
   * Previous step submit handler.
   */
  public function previousStep(array &$form, FormStateInterface $form_state) {
    $step = $form_state->get('step') ?? 1;
    $form_state->set('step', $step - 1);
    $form_state->setRebuild();
  }

}