<?php

namespace Application;

use Zend\Mvc\ModuleRouteListener,
    Zend\Mvc\MvcEvent;

class Module {
  
public function init(\Zend\ModuleManager\ModuleManager $moduleManager) {
       
        $full_path = __DIR__ . '/view/layout/layout.phtml';
        define('ROOT_PATH', $full_path);
        define('total_records','5');
 	$sharedEvents = $moduleManager->getEventManager()->getSharedManager();
   	$sharedEvents->attach(__NAMESPACE__, 'dispatch', function($e) {
	$authService = new \Zend\Authentication\AuthenticationService;
        $authStorage = $authService->getStorage();
 //       $user = $authStorage->read();
    //  $username = $user->getName();
    //    exit;
        $controller = $e->getTarget();
        // checking for category id and its existence for products controller
	if($authStorage->isEmpty()){
           $controller->redirect()->toRoute('session');
        }
        else {
           $controller->redirect()->toRoute('company');
        }
	if (is_readable(ROOT_PATH)) {
		  $controller->layout('application');
		}
	}, 2);
    }

  public function getConfig() {
    return include __DIR__ . '/config/module.config.php';
  }

  public function getAutoloaderConfig() {
    return array(
      'Zend\Loader\StandardAutoloader' => array(
        'namespaces' => array(
          __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
        ),
      ),
    );
  }

  public function getServiceConfig() {
    return array(
      'factories' => array(
        'Company' => function($sm) {
          $em = $sm->get('doctrine.entitymanager.orm_default');
          $company = new Company($em);
          return $company;
        },
        'Country' => function($sm) {
          $em = $sm->get('doctrine.entitymanager.orm_default');
          $country = new Country($em);
          return $country;
        },
        'Department' => function($sm) {
          $em = $sm->get('doctrine.entitymanager.orm_default');
          $department = new Department($em);
          return $department;
        },
        'User' => function($sm) {
          $em = $sm->get('doctrine.entitymanager.orm_default');
          $user = new User($em);
          return $user;
        },
      ),
      'Requesthelper' => function($sm) {
          $helper = new \Company\Helper\RequestHelper;
          $request = $sm->getServiceLocator()->get('Request');
          $helper->setRequest($request);
          return $helper;
        }
    );
    
    }
    
     public function getViewHelperConfig() {
    return array(
      'factories' => array(
        'ControllerName' => function ($sm) {
          $match = $sm->getServiceLocator()->get('application')->getMvcEvent()->getRouteMatch();
          $viewHelper = new \Application\View\Helper\ControllerName($match);
          return $viewHelper;
        },
        'ActionName' => function ($sm) {
          $match = $sm->getServiceLocator()->get('application')->getMvcEvent()->getRouteMatch();
          $viewHelper = new \Application\View\Helper\ActionName($match);
          return $viewHelper;
        },
        'FullUrl' => function ($sm) {
          $match = $sm->getServiceLocator()->get('application')->getMvcEvent()->getRouteMatch();
          $fullUrlHelper = new \Application\View\Helper\FullUrl($match);
          $request = $sm->getServiceLocator()->get('Request');
          $fullUrlHelper->setRequest($request);
          return $fullUrlHelper;
        },
        'flashMessage' => function($sm) {
          $flashmessenger = $sm->getServiceLocator()
            ->get('ControllerPluginManager')
            ->get('flashmessenger');

          $message = new \Application\View\Helper\FlashMessages( );
          $message->setFlashMessenger($flashmessenger);

          return $message;
        }
      ),
    );
  }
  
   public function loadConfiguration(MvcEvent $e) {
    $application = $e->getApplication();
    $sm = $application->getServiceManager();
    $sharedManager = $application->getEventManager()->getSharedManager();

    $sharedManager->attach('Zend\Mvc\Controller\AbstractActionController', 'dispatch', function($e) use ($sm) {
        $sm->get('ControllerPluginManager')->get('Aclplugin'); //pass to the plugin...    
      }, 2
    );
  }

 
}
