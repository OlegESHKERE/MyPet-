<?php

namespace Drupal\my_pet_assistant\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\my_pet_assistant\Service\AIChatService;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;

class ChatForm extends FormBase {

  protected $aiChatService;
  protected $currentUser;

  public function __construct(AIChatService $aiChatService, AccountProxyInterface $currentUser) {
    $this->aiChatService = $aiChatService;
    $this->currentUser = $currentUser;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('my_pet_assistant.ai_chat_service'),
      $container->get('current_user')
    );
  }

  public function getFormId() {
    return 'my_pet_assistant_chat_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    // Wrap the entire form for AJAX replacement.
    $form['#prefix'] = '<div id="my-pet-assistant-chat-form-wrapper">';
    $form['#suffix'] = '</div>';

    // Get chat history from session.
    $session = \Drupal::request()->getSession();
    $chat_history = $session->get('my_pet_assistant_chat_history', []);

    // Render chat bubbles.
    $messages_markup = '<div class="chat-history">';
    foreach ($chat_history as $msg) {
      $is_user = $msg['sender'] === 'user';
      $row_class = $is_user ? 'message-row user-row' : 'message-row ai-row';
      $bubble_class = $is_user ? 'user-message' : 'ai-message';
      $messages_markup .= '<div class="' . $row_class . '">';
      $messages_markup .= '<div class="' . $bubble_class . '">';
      $messages_markup .= nl2br(htmlspecialchars($msg['text']));
      $messages_markup .= '<div class="timestamp">' . $msg['time'] . '</div>';
      $messages_markup .= '</div></div>';
    }
    $messages_markup .= '</div>';

    $form['chat_history'] = [
      '#type' => 'markup',
      '#markup' => $messages_markup,
    ];

    $form['user_prompt'] = [
      '#type' => 'textarea',
      '#title' => '',
      '#attributes' => [
        'placeholder' => $this->t('Type your message…'),
        'rows' => 2,
        'class' => ['chat-input'],
        'style' => 'resize:none;',
      ],
      '#default_value' => '', // Always clear after send.
    ];

    $form['send'] = [
      '#type' => 'submit', // <- Use submit, not button!
      '#value' => $this->t('Send'),
      '#ajax' => [
        'callback' => '::ajaxSubmit',
        'wrapper' => 'my-pet-assistant-chat-form-wrapper',
        'progress' => [
          'type' => 'throbber',
          'message' => $this->t('Sending...'),
        ],
      ],
      '#attributes' => [
        'class' => ['chat-send-button'],
      ],
    ];

    return $form;
  }

  // AJAX callback: receives the rebuilt form as $form!
  public function ajaxSubmit(array &$form, FormStateInterface $form_state) {
    return $form;
  }

  // Standard submit handler: process and store chat, then rebuild.
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $session = \Drupal::request()->getSession();
    $prompt = $form_state->getValue('user_prompt');

    // Get chat history.
    $chat_history = $session->get('my_pet_assistant_chat_history', []);

    // Add user's message.
    if (!empty(trim($prompt))) {
      $chat_history[] = [
        'sender' => 'user',
        'text' => $prompt,
        'time' => date('g:i A'),
      ];

      // Prepare user data for AI.
      $user = $this->currentUser;
      $user_data = [
        'name' => $user->getDisplayName(),
        'roles' => $user->getRoles(),
        'uid' => $user->id(),
      ];
      $full_prompt = "User data: " . json_encode($user_data) . "\nUser question: " . $prompt;

      // Get AI response.
      $ai_response = $this->aiChatService->askAI($full_prompt);

      // Add AI's message.
      $chat_history[] = [
        'sender' => 'ai',
        'text' => $ai_response,
        'time' => date('g:i A'),
      ];
    }

    // Save updated chat history.
    $session->set('my_pet_assistant_chat_history', $chat_history);

    // Clear textarea for next input.
    $form_state->setValue('user_prompt', '');

    // Rebuild form for AJAX.
    $form_state->setRebuild(TRUE);
  }
}

