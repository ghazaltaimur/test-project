<?php

/**
 * @namespace
 */

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class FlashMessages extends AbstractHelper {

  protected $flashMessenger;

  public function setFlashMessenger($flashMessenger) {
    $this->flashMessenger = $flashMessenger;
  }

  public function __invoke() {

    $namespaces = array(
      'error', 'success',
      'info', 'warning'
    );

    // messages as string
    $messageString = '';

    foreach ($namespaces as $ns) {

      $this->flashMessenger->setNamespace($ns);

      $messages = array_merge(
        $this->flashMessenger->getMessages(), $this->flashMessenger->getCurrentMessages()
      );


      if (!$messages)
        continue;

      $messageString[$ns] = implode('<br />', $messages);
    }    
    return $messageString;
  }

}