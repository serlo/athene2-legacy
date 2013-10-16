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
namespace Alias;

return array(
    'class_resolver' => array(
        __NAMESPACE__ . '\Entity\AliasInterface' => __NAMESPACE__ . '\Entity\Alias'
    ),
    'router' => array(
        'routes' => array(
            'alias' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/alias/:alias',
                    'defaults' => array(
                        'controller' => 'Alias\Controller\AliasController',
                        'action' => 'forward'
                    ),
                    'constraints' => array(
                        'alias' => '(.)+'
                    )
                ),
                'may_terminate' => true
            )
        )
    ),
    'di' => array(
        'allowed_controllers' => array(
            'Alias\Controller\AliasController'
        ),
        'definition' => array(
            'class' => array(
                'Alias\Controller\AliasController' => array(
                    'setAliasManager' => array(
                        'required' => 'true'
                    ),
                    'setLanguageManager' => array(
                        'required' => 'true'
                    )
                ),
                'Alias\AliasManager' => array(
                    'setObjectManager' => array(
                        'required' => 'true'
                    ),
                    'setClassResolver' => array(
                        'required' => 'true'
                    )
                )
            )
        ),
        'instance' => array(
            'preferences' => array(
                __NAMESPACE__ . '\AliasManagerInterface' => __NAMESPACE__ . '\AliasManager'
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

