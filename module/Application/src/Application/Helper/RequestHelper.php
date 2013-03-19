<?php
namespace Application\Helper;

use Zend\View\Helper\AbstractHelper;

class RequestHelper extends AbstractHelper
{
    protected $request;
    
    //get Request
    public function setRequest($request)
    {
        $this->request = $request;
    }
    
    public function getRequest()
    {
	
        return $this->request;    
    }
    
    public function __invoke()
    {
        return $this->getRequest()->getServer()->get('QUERY_STRING');
    }
    
    public function getFullUrl(){
      $server_name = $this->getRequest()->getServer()->get('SERVER_NAME');
      $request_uri = $this->getRequest()->getServer()->get('REQUEST_URI');
      return $server_name.$request_uri;
    }
}

?>
