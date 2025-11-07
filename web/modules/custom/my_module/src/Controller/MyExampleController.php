<?php

namespace Drupal\my_module\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\my_module\Service\MyExampleService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Приклад контролера з DI.
 */
class MyExampleController extends ControllerBase {

  /**
   * Сервіс для роботи з даними.
   *
   * @var \Drupal\my_module\Service\MyExampleService
   */
  protected $exampleService;

  /**
   * Конструктор із впровадженням залежності.
   *
   * @param \Drupal\my_module\Service\MyExampleService $exampleService
   */
  public function __construct(MyExampleService $exampleService) {
    $this->exampleService = $exampleService;
  }

  /**
   * Визначає, як створити обʼєкт через контейнер.
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('my_module.example_service')
    );
  }

  /**
   * Метод-контролер для рендеру сторінки.
   */
  public function content() {
    $data = $this->exampleService->getAllData();

    $output = ['#theme' => 'item_list', '#items' => []];

    foreach ($data as $record) {
      $output['#items'][] = $record->name ?? 'Без імені';
    }

    return $output;
  }
}
