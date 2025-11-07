<?php

namespace Drupal\my_module\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

/**
 * Клас слушача подій, який реагує на подію відповіді сервера.
 * Важливо: обов'язково реалізувати EventSubscriberInterface.
 */
class MyExampleSubscriber implements EventSubscriberInterface {

  /**
   * Метод, що повертає події, на які підписується цей слухач та методи обробки.
   *
   * @return array
   *   Масив подій у вигляді ['eventName' => 'callbackMethod'].
   */
  public static function getSubscribedEvents() {
    return [
      KernelEvents::RESPONSE => 'onRespond',
    ];
  }

  /**
   * Метод, що виконується при події RESPONSE.
   *
   * @param \Symfony\Component\HttpKernel\Event\ResponseEvent $event
   *   Обʼєкт події відповіді від сервера.
   */
  public function onRespond(ResponseEvent $event) {
    // Тут ваша логіка, що виконується при відповіді.
  }

}
