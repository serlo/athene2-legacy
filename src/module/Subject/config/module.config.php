<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Subject;

return array(
    'service_manager' => [
        'factories' => [
            __NAMESPACE__ . '\Options\ModuleOptions'  => __NAMESPACE__ . '\Factory\ModuleOptionsFactory',
            __NAMESPACE__ . '\Manager\SubjectManager' => __NAMESPACE__ . '\Factory\SubjectManagerFactory',
            __NAMESPACE__ . '\Hydrator\Navigation'    => __NAMESPACE__ . '\Factory\NavigationFactory'
        ]
    ],
    'view_helpers'    => [
        'factories' => [
            'subject' => __NAMESPACE__ . '\Factory\SubjectHelperFactory'
        ]
    ],
    'taxonomy'        => array(
        'types' => array(
            'topic-final-folder'      => array(
                'allowed_associations' => array(
                    'entities'
                ),
                'allowed_parents'      => array(
                    'topic-folder'
                ),
                'rootable'             => false
            ),
            'topic-folder'            => array(
                'allowed_associations' => array(
                    'entities'
                ),
                'allowed_parents'      => array(
                    'topic'
                ),
                'rootable'             => false
            ),
            'topic'                   => array(
                'allowed_parents' => array(
                    'subject',
                    'topic'
                ),
                'rootable'        => false
            ),
            'subject'                 => array(
                'allowed_parents' => array(
                    'root'
                ),
                'rootable'        => false
            ),
            'locale'                  => array(
                'allowed_parents' => array(
                    'subject',
                    'locale'
                ),
                'rootable'        => false
            ),
            'curriculum'              => array(
                'allowed_parents' => array(
                    'subject',
                    'locale'
                ),
                'rootable'        => false
            ),
            'curriculum-folder'       => array(
                'allowed_associations' => array(
                    'entities'
                ),
                'allowed_parents'      => array(
                    'curriculum',
                    'curriculum-folder'
                ),
                'rootable'             => false
            ),
            'curriculum-final-folder' => array(
                'allowed_associations' => array(
                    'entities'
                ),
                'allowed_parents'      => array(
                    'curriculum-folder'
                ),
                'rootable'             => false
            )
        )
    ),
    'router'          => array(
        'routes' => array(
            'subject' => array(
                'type'          => 'Zend\Mvc\Router\Http\Segment',
                'may_terminate' => true,
                'options'       => array(
                    'route'    => '/{subject}/:subject',
                    'defaults' => array(
                        'controller' => __NAMESPACE__ . '\Controller\HomeController',
                        'action'     => 'index'
                    )
                ),
                'child_routes'  => array(
                    'taxonomy' => array(
                        'type'    => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route'       => '[/:path/]',
                            'defaults'    => array(
                                'controller' => __NAMESPACE__ . '\Controller\TaxonomyController',
                                'action'     => 'index'
                            ),
                            'constraints' => array(
                                'path' => '(.)+'
                            )
                        )
                    ),
                    'entity'   => array(
                        'may_terminate' => true,
                        'type'          => 'Zend\Mvc\Router\Http\Segment',
                        'options'       => array(
                            'route'    => '/entity/:action',
                            'defaults' => array(
                                'controller' => __NAMESPACE__ . '\Controller\EntityController',
                                'action'     => 'index',
                                'plugin'     => 'entity'
                            )
                        )
                    )
                )
            )
        )
    ),
    'di'              => array(
        'allowed_controllers' => array(
            __NAMESPACE__ . '\Controller\TaxonomyController',
            __NAMESPACE__ . '\Controller\EntityController',
            __NAMESPACE__ . '\Controller\HomeController'
        ),
        'definition'          => array(
            'class' => array(
                __NAMESPACE__ . '\Controller\HomeController'     => array(
                    'setInstanceManager' => array(
                        'required' => true
                    ),
                    'setSubjectManager'  => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\Controller\TaxonomyController' => array(
                    'setInstanceManager' => array(
                        'required' => true
                    ),
                    'setSubjectManager'  => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\Controller\EntityController'   => array(
                    'setInstanceManager' => array(
                        'required' => true
                    ),
                    'setSubjectManager'  => array(
                        'required' => true
                    )
                )
            )
        ),
        'instance'            => array(
            'preferences' => array(
                __NAMESPACE__ . '\Manager\SubjectManagerInterface' => __NAMESPACE__ . '\Manager\SubjectManager'
            )
        )
    )
);