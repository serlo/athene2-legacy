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
            'adapters' => array()
        )
    ),
    'class_resolver' => array(
        'Contexter\Entity\ContextInterface' => 'Contexter\Entity\Context',
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
    'service_manager' => array(
        'factories' => array(
            __NAMESPACE . '\Router\Router' => function (ServiceLocatorInterface $serviceManager)
            {
                $config = $serviceManager->get('config');
                $instance = new Router\Router();
                $instance->setConfig($config['contexter']['router']);
                $instance->setRouteMatch($serviceManager->get('Application')
                    ->getMvcEvent()
                    ->getRouteMatch());
                $instance->setServiceLocator($serviceManager);
                $instance->setObjectManager($serviceManager->get('EntityManager'));
                $instance->setClassResolver($serviceManager->get('ClassResolver\ClassResolver'));
                $instance->setContexter($serviceManager->get('Contexter\Contexter'));
                return $instance;
            },
            __NAMESPACE . '\Context' => function (ServiceLocatorInterface $serviceManager)
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
            __NAMESPACE . '\Controller\DiscussionController'
        ),
        'definition' => array(
            'class' => array(
                __NAMESPACE . '\Context' => array(
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
                __NAMESPACE . '\Contexter' => __NAMESPACE . '\Contexter',
                __NAMESPACE . '\Router\RouterInterface' => __NAMESPACE . '\Router\Router'
            ),
            __NAMESPACE . '\Context'  => array(
                'shared' => false
            )
        )
    )
);