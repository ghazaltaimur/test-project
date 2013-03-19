<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class FullUrl extends AbstractHelper {

  protected $request;

  //get Request
  public function setRequest($request) {
    $this->request = $request;
  }

  public function getRequest() {
    return $this->request;
  }

  public function __invoke($current_page = true) {
    return $this->getFullUrl($current_page);
  }

  public function getFullUrl($current_page = true) {
    $server_name = $this->getRequest()->getServer()->get('SERVER_NAME');
    $request_uri = $this->getRequest()->getServer()->get('REQUEST_URI');
    $http_referer = $this->getRequest()->getServer()->get('HTTP_REFERER');
    if ($current_page)
      return $server_name . $request_uri;
    else
      return $http_referer;
  }

}

?>