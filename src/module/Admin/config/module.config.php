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
namespace Admin;

return array(
    'navigation' => array(
        'default' => array(
            'restricted' => array(
                'label' => 'Area 51',
                'route' => 'restricted'
            )
        )
    ),
    'router' => array(
        'routes' => array(
            'restricted' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/restricted',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\HomeController',
                        'action' => 'index'
                    )
                ),
                'may_terminate' => true
            )
        )
    ),
    'di' => array(
        'allowed_controllers' => array(
            'Admin\Controller\HomeController'
        ),
        'definition' => array(
            'class' => array(
                'Admin\Controller\HomeController' => array(
                    'setEntityManager' => array(
                        'required' => 'true'
                    ),
                    'setLanguageManager' => array(
                        'required' => 'true'
                    ),
                    'setUserManager' => array(
                        'required' => 'true'
                    )
                )
            )
        )
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
    )
);

