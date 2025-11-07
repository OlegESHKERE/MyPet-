<?php

namespace Drupal\my_pet_assistant\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\my_pet_assistant\Form\ChatForm;

class ChatController extends ControllerBase {

  public function chatPage() {
    $form = $this->formBuilder()->getForm(ChatForm::class);
    return [
      '#title' => $this->t('My Pet Assistant Chat'),
      'chat_form' => $form,
    ];
  }

}
