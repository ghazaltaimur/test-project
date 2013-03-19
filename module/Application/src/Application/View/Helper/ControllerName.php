<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class ControllerName extends AbstractHelper {
  protected $routeMatch;

  public function __construct($routeMatch) {
    $this->routeMatch = $routeMatch;
  }

  public function __invoke() {
    $controller = ($this->routeMatch)?$this->routeMatch->getParam('controller', 'index'):'';
    //print_r($this->routeMatch);die;
    return $controller;
  }
}