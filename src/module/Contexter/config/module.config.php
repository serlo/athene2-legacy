<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author	Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Contexter;

use Zend\ServiceManager\ServiceLocatorInterface;
return array(
    'contexter' => array(
        'router' => array(
            'adapters' => array(
                array(
                    'adapter' => __NAMESPACE__ . '\Adapter\EntityPluginControllerAdapter',
                    'controllers' => array(
                        'LearningResource\Plugin\Repository\Controller\RepositoryController'
                    )
                )
            )
        )
    ),
    'view_helpers' => array(
        'factories' => array(
            'contexter' => function ($helperPluginManager)
            {
                $plugin = new \Contexter\View\Helper\Contexter();
                $plugin->setRouter($helperPluginManager->getServiceLocator()
                    ->get('Contexter\Router\Router'));
                return $plugin;
            }
        )
    ),
    'class_resolver' => array(
        'Contexter\Entity\ContextInterface' => 'Contexter\Entity\Context',
        'Contexter\ContextInterface' => 'Contexter\Context',
        'Contexter\Entity\TypeInterface' => 'Contexter\Entity\Type',
        'Contexter\Entity\RouteInterface' => 'Contexter\Entity\Route',
        'Contexter\Entity\RouteParameterInterface' => 'Contexter\Entity\RouteParameter'
    ),
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity'
                )
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        )
    ),
    'router' => array(
        'routes' => array(
            'contexter' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/context',
                    'defaults' => array(
                        'controller' => __NAMESPACE__ . '\Controller\ContextController'
                    )
                ),
                'child_routes' => array(
                    'select-uri' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/select-uri',
                            'defaults' => array(
                                'action' => 'selectUri'
                            )
                        )
                    ),
                    'add' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/add',
                            'defaults' => array(
                                'action' => 'add'
                            )
                        )
                    ),
                    'discussions' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/manage',
                            'defaults' => array(
                                'action' => 'manage'
                            )
                        )
                    )
                )
            )
        )
    ),
    'service_manager' => array(
        'factories' => array(
            __NAMESPACE__ . '\Router\Router' => function (ServiceLocatorInterface $serviceManager)
            {
                $config = $serviceManager->get('config');
                $instance = new Router\Router();
                $instance->setConfig($config['contexter']['router']);
                $instance->setServiceLocator($serviceManager);
                $instance->setRouter($serviceManager->get('Router'));
                $instance->setRouteMatch($serviceManager->get('Application')
                    ->getMvcEvent()
                    ->getRouteMatch());
                $instance->setObjectManager($serviceManager->get('EntityManager'));
                $instance->setClassResolver($serviceManager->get('ClassResolver\ClassResolver'));
                $instance->setContexter($serviceManager->get('Contexter\Contexter'));
                return $instance;
            },
            __NAMESPACE__ . '\Context' => function (ServiceLocatorInterface $serviceManager)
            {
                $instance = new Context();
                $instance->setClassResolver($serviceManager->get('ClassResolver\ClassResolver'));
                $instance->setRouter($serviceManager->get('Router'));
                $instance->setObjectManager($serviceManager->get('EntityManager'));
                return $instance;
            }
        )
    ),
    'di' => array(
        'allowed_controllers' => array(
            __NAMESPACE__ . '\Controller\ContextController'
        ),
        'definition' => array(
            'class' => array(
                __NAMESPACE__ . '\Adapter\EntityPluginControllerAdapter' => array(
                    'setLanguageManager' => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\Controller\ContextController' => array(
                    'setUuidManager' => array(
                        'required' => true
                    ),
                    'setContexter' => array(
                        'required' => true
                    ),
                    'setRouter' => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\Context' => array(
                    'setServiceLocator' => array(
                        'required' => true
                    ),
                    'setObjectManager' => array(
                        'required' => true
                    ),
                    'setClassResolver' => array(
                        'required' => true
                    ),
                    'setRouter' => array(
                        'required' => true
                    )
                )
            )
        ),
        'instance' => array(
            'preferences' => array(
                __NAMESPACE__ . '\ContexterInterface' => __NAMESPACE__ . '\Contexter',
                __NAMESPACE__ . '\Router\RouterInterface' => __NAMESPACE__ . '\Router\Router'
            ),
            __NAMESPACE__ . '\Context' => array(
                'shared' => false
            )
        )
    )
);

