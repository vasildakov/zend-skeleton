<?php
namespace Application;
return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),           
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        __NAMESPACE__ => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
            'doctrine.cache.apc' => function(\Zend\ServiceManager\ServiceManager $sm) {
                 return new \Doctrine\Common\Cache\ApcCache();
            },
            'doctrine.cache.memcache' => function(\Zend\ServiceManager\ServiceManager $sm) {
                $cache = new \Doctrine\Common\Cache\MemcacheCache();
                $memcache = new \Memcache();
                $memcache->connect('localhost', 11211);
                $cache->setMemcache($memcache);
                return $cache;                
            }
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            // __NAMESPACE__ . '_driver' => array(
                'Application_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                // 'cache' => 'apc',
                // 'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
                'paths' => array(__DIR__ . '/../src/Application/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    // __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                    'Application\Entity' => 'Application_driver'
                ),
            ),
            'orm_customer' => array(
                'drivers' => array(
                    // __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                    'Application\Entity' => 'Application_driver' 
                    )
            )
        ),
    ), 
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController',
            //'Application\Controller\Auth' => 'Application\Controller\AuthController'
        ),
    ),
    'controller_plugins' => array(
        'invokables' => array(
            'SystemPlugin' => 'Application\Controller\Plugin\SystemPlugin',
            'DecoratorPlugin' => 'Application\Controller\Plugin\DecoratorPlugin',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
