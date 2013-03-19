<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class ActionName extends AbstractHelper {
  protected $routeMatch;

  public function __construct($routeMatch) {
    $this->routeMatch = $routeMatch;
  }

  public function __invoke() {
    $action = ($this->routeMatch)?$this->routeMatch->getParam('action'):'';
    return $action;
  }
}