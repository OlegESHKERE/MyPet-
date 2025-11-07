<?php

namespace Drupal\my_module\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Простий приклад форми.
 */
class MyExampleForm extends FormBase {

  /**
   * Ідентифікатор форми.
   */
  public function getFormId() {
    return 'my_example_form';
  }

  /**
   * Побудова форми.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['your_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Ваше ім\'я'),
      '#required' => TRUE,
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Відправити'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * Обробка відправки форми.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $name = $form_state->getValue('your_name');
    $this->messenger()->addMessage($this->t('Привіт, @name!', ['@name' => $name]));
  }
}
