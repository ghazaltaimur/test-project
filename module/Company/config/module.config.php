<?php
namespace Company;

return array(
    
    // Controllers in this module
    'controllers' => array(
        'invokables' => array(
            'Company\Controller\Company' => 'Company\Controller\CompanyController',
            'Company\Controller\Department' => 'Company\Controller\DepartmentController'
            
        ),
    ),

    
    // Routes for this module
    'router' => array(
        'routes' => array(
            
            'company' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/company[/:page][/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                        'page' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Company\Controller\Company',
                        'action'     => 'index',
                        'page' => 1,
                    ),
                ),
            ), 
            'department' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/department[/:page][/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                        'page' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Company\Controller\Department',
                        'action'     => 'index',
                        'page' => 1,
                    ),
                ),
            ), 
        ),
    ),
    
    // View setup for this module
    'view_manager' => array(
        'template_path_stack' => array(
            'company' => __DIR__ . '/../view',
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
            'configuration' => array(
            'odm_default' => array(
                'metadata_cache'     => 'array',
                'driver'             => 'odm_default',
                'generate_proxies'   => true,
                'proxy_dir'          => 'data/DoctrineORMModule/Proxy',
                'proxy_namespace'    => 'DoctrineORMModule\Proxy',
                'generate_hydrators' => true,
                'hydrator_dir'       => 'data/DoctrineORMModule/Hydrator',
                'hydrator_namespace' => 'DoctrineORMModule\Hydrator',
                'default_db'         => 'test',
                'filters'            => array()
            )
        ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        )
    ),
    
    'view_helpers' => array(
        'invokables' => array(
          'Requesthelper' => 'Company\Helper\Requesthelper',  
        )
    ),

    
);
