<?php
namespace Session;

return array(
    
    // Controllers in this module
    'controllers' => array(
        'invokables' => array(
            'Session\Controller\Session' => 'Session\Controller\SessionController',
        ),
    ),

    
    // Routes for this module
    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'session' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/login',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Session\Controller\Session',
                        'action'     => 'login',
                    ),
                ),
            ),
        ),
    ),
    
  //   View setup for this module
    'view_manager' => array(
        'template_path_stack' => array(
            'session' => __DIR__ . '/../view',
        ),
    ),
    
    // Doctrine config
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        ),
        
    ),
    
//    'view_helpers' => array(
//        'invokables' => array(
//          'Requesthelper' => 'User\Helper\Requesthelper',  
//        )
//    ),

    
);
