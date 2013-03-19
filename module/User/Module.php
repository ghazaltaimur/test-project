<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace User;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Controller\Plugin;
use Zend\Http\PhpEnvironment\Response;
use User\Controller\UserController;
use User\Model\User;

class Module {
    
    public function init(\Zend\ModuleManager\ModuleManager $moduleManager) {	
 	$sharedEvents = $moduleManager->getEventManager()->getSharedManager();
   	$sharedEvents->attach(__NAMESPACE__, 'dispatch', function($e) {
	$authService = new \Zend\Authentication\AuthenticationService;
        $authStorage = $authService->getStorage();
        $controller = $e->getTarget();
        
	if($authStorage->isEmpty()){
            $controller->redirect()->toRoute('session');
        }
        else {
         //   exit;
            if (is_readable(ROOT_PATH)) {
		  $controller->layout('application');
		}
          
        }
      }, 2);
    }
    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
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
        'User' => function($sm) {
          $em = $sm->get('doctrine.entitymanager.orm_default');
          $user = new User($em);
          return $user;
        },
        
      )
    );
    
    }

}
