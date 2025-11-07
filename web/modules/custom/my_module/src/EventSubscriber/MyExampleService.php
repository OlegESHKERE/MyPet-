<?php

namespace Drupal\my_module\Service;

use Drupal\Core\Database\Connection;

/**
 * Клас сервісу для отримання даних з бази.
 */
class MyExampleService {

  /**
   * Змінна для збереження підключення до бази.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Конструктор з впровадженням залежності.
   *
   * @param \Drupal\Core\Database\Connection $database
   *   Обʼєкт для роботи з БД.
   */
  public function __construct(Connection $database) {
    $this->database = $database;
  }

  /**
   * Метод для отримання всіх записів з таблиці example.
   *
   * @return array
   *   Масив обʼєктів з бази.
   */
  public function getAllData(): array {
    $query = $this->database->select('example', 'e')
      ->fields('e')
      ->execute();

    return $query->fetchAll();
  }
}
