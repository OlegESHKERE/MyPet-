<?php

namespace Drupal\my_pet_assistant\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class AIChatService {

  protected $httpClient;
  protected $apiKey;
  protected $apiUrl;

  public function __construct() {
    $this->httpClient = new Client();
    $this->apiKey = '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'; // Вставте сюди ваш ключ
    $this->apiUrl = 'https://openrouter.ai/api/v1/chat/completions';
  }

  public function askAI(string $prompt): string {
    $payload = [
      'model' => 'deepseek/deepseek-chat:free',
      'messages' => [
        ['role' => 'system', 'content' => 'You are a helpful assistant.'],
        ['role' => 'user', 'content' => $prompt],
      ],
      'stream' => false,
    ];

    try {
      $response = $this->httpClient->post($this->apiUrl, [
        'headers' => [
          'Authorization' => 'Bearer ' . $this->apiKey,
          'Content-Type' => 'application/json',
        ],
        'json' => $payload,
      ]);

      $data = json_decode($response->getBody()->getContents(), TRUE);

      // Відповідь знаходиться тут (залежить від формату API)
      if (isset($data['choices'][0]['message']['content'])) {
        return $data['choices'][0]['message']['content'];
      }

      return 'No response from AI.';
    }
    catch (RequestException $e) {
      watchdog_exception('my_pet_assistant', $e);
      return 'Error communicating with AI service.';
    }
  }

}
