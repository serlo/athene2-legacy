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
namespace Search;

use Zend\ServiceManager\ServiceLocatorInterface;
return array(
    'search' => array(),
    'service_manager' => array(
        'factories' => array(
            'Foolz\SphinxQL\Connection' => function (ServiceLocatorInterface $serviceLocator)
            {
                $config = $serviceLocator->get('config');
                $config = $config['sphinx'];
                $instance = new \Foolz\SphinxQL\Connection();
                $instance->setConnectionParams($config['host'], $config['port']);
                return $instance;
            },
            __NAMESPACE__ . '\SearchService' => function (ServiceLocatorInterface $serviceLocator)
            {
                $config = $serviceLocator->get('config');
                $config = $config['search'];
                $instance = new SearchService();
                $instance->setServiceLocator($serviceLocator);
                $instance->setConfig($config);
                return $instance;
            }
        )
    ),
    'di' => array(
        'allowed_controllers' => array(
            __NAMESPACE__ . '\Controller\SearchController'
        ),
        'definition' => array(
            'class' => array(
                __NAMESPACE__ . '\Adapter\SphinxQL\EntityAdapter' => array(
                    'setConnection' => array(
                        'required' => true
                    ),
                    'setEntityManager' => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\Controller\SearchController' => array(
                    'setSearchService' => array(
                        'required' => true
                    )
                )
            )
        ),
        'instance' => array(
            'preferences' => array(
                __NAMESPACE__ . '\SearchServiceInterface' => __NAMESPACE__ . '\SearchService'
            )
        )
    ),
    'router' => array(
        'routes' => array(
            'search' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/search',
                    'defaults' => array(
                        'controller' => __NAMESPACE__ . '\Controller\SearchController',
                        'action' => 'search'
                    )
                )
            )
        )
    )
);