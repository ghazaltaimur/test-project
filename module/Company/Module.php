<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Company;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Company\Model\Company;
use Company\Model\Country;
use Company\Model\Department;

class Module
{
   public function init(\Zend\ModuleManager\ModuleManager $moduleManager) {	
 	$sharedEvents = $moduleManager->getEventManager()->getSharedManager();
   	$sharedEvents->attach(__NAMESPACE__, 'dispatch', function($e) {
        $controller = $e->getTarget();
        $authService = new \Zend\Authentication\AuthenticationService;
        $authStorage = $authService->getStorage();
        $user = $authStorage->read();
        if($authStorage->isEmpty()){
           $controller->redirect()->toRoute('user');
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
        
      )
    );
    
    }
  
}
