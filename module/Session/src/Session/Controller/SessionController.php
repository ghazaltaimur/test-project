<?php
namespace Session\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel,
    Session\Model\SessionModel;
use User\Entity\User as UserEntity;          // <-- Add this import
use Session\Form\SessionForm;
use Zend\Authentication\Storage\Session as SessionLogin;
use Doctrine\ORM\EntityManager;
use Zend\Session\Container;
use Zend\Http\Request as HttpRequest,
    Zend\Paginator\Paginator,
    DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter,
    Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;

class SessionController extends AbstractActionController 
{
    /**             
    * @var Doctrine\ORM\EntityManager
    */  
    protected $em;
    
    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }
    
    public function getEntityManager()
    {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }
        return $this->em;
    }
    
    public function getAuthService()
    {
        if (! $this->authservice) {
            $this->authservice = $this->getServiceLocator()
                                      ->get('AuthService');
        }
        
        return $this->authservice;
    }
    
    public function loginAction()
    {
	$form = new SessionForm(array(),array());
        $Session_session = new Container('Session');
       
        $request = $this->getRequest();
        $flash = $this->flashMessenger()->getMessages();
       // print_r($flash);
        if ($request->isPost()) {
            $Session = new SessionModel(array());
            $form->setInputFilter($Session->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
              $data = $form->getData();
              $form->setData($data);
            //  echo "here";
                 //   $lastId = $this->getSessionTable()->saveSession($Session);
               $em = $this->getEntityManager();

               $adapter = new \DoctrineModule\Authentication\Adapter\ObjectRepository(array(
                    'objectManager' => $em,
                    'identityClass' => 'User\Entity\User',
                    'identityProperty' => 'email',
                    'credentialProperty' => 'password',
                   // 'credentialCallable' => 'Session\Entity\Session::hashPassword'
                  ));
               $adapter->setIdentityValue($data['email'])->setCredentialValue(md5($data['password']));
               $authService = new \Zend\Authentication\AuthenticationService;
              
               $result = $authService->authenticate($adapter);
             //  echo "here";

                //set flash message
               
               if ($result->isValid()) {
                   return $this->redirect()->toRoute('company');
               }
               else {
                 $this->flashMessenger()->setNamespace('warning')
                    ->addMessage('Invalid Username or Password!');
                  }
                  //  die("1");    
                                
                // Redirect to list of Session
           //     return $this->redirect()->toRoute('Session');
            }
            
        }
        
        return array('form' => $form);
    }
  public function destroyAction() {
        $authService = new \Zend\Authentication\AuthenticationService;
        $authStorage = $authService->getStorage();
        $authStorage->clear();
        return $this->redirect()->toRoute('session');
  }
}
