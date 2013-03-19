<?php
namespace Company\Helper;
use Zend\View\Helper\AbstractHelper;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;

class Requesthelper extends AbstractHelper
{
    protected $request;
    //get Request
    public function setRequest($request)
    {
        $this->request = $request;    
    }
    
    public function getRequest()
    {
//	/$this->request;
        return $this->request;    
    }
    
    public function __invoke()
    {
	//echo "here".$this->getRequest().$this->getRequest()->getServer();
        return $this->getRequest()->getServer()->get('QUERY_STRING');     
    }
    
    public function getFullUrl(){
      $server_name = $this->getRequest()->getServer()->get('SERVER_NAME');
      $request_uri = $this->getRequest()->getServer()->get('REQUEST_URI');
      return $server_name.$request_uri;
    }
}
