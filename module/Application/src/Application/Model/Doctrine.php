<?php

namespace Application\Model;

class Doctrine {

  protected $entityManager;

  public function __construct($em) {
    $this->entityManager = $em;
  }

}
