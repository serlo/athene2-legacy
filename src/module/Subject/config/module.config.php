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
namespace Subject;

use Subject\View\Helper\SubjectHelper;
return array(
    'navigation' => array(
        'hydrateables' => array(
            'default' => array(
                'hydrators' => array(
                    'Subject\Hydrator\Navigation'
                )
            )
        )
    ),
    'service_manager' => [
        'factories' => [
            __NAMESPACE__ . '\Options\ModuleOptions' => __NAMESPACE__ . '\Factory\ModuleOptionsFactory'
        ]
    ],
    'view_helpers' => [
        'factories' => [
            'subject' => function ($helperPluginManager)
            {
                $plugin = new SubjectHelper();
                $plugin->setModuleOptions($helperPluginManager->getServiceLocator()
                    ->get('Subject\Options\ModuleOptions'));
                return $plugin;
            }
        ]
    ],
    'term_router' => array(
        'routes' => array(
            'topic' => array(
                'route' => 'subject/plugin/taxonomy/topic',
                'param_provider' => 'Subject\Provider\ParamProvider'
            ),
            'topic-folder' => array(
                'route' => 'subject/plugin/taxonomy/topic',
                'param_provider' => 'Subject\Provider\ParamProvider'
            ),
            'abstract-topic' => array(
                'route' => 'subject/plugin/taxonomy/topic',
                'param_provider' => 'Subject\Provider\ParamProvider'
            ),
            'curriculum-folder' => array(
                'route' => 'subject/plugin/taxonomy/curriculum',
                'param_provider' => 'Subject\Provider\ParamProvider'
            ),
            'school-type' => array(
                'route' => 'subject/plugin/taxonomy/curriculum',
                'param_provider' => 'Subject\Provider\ParamProvider'
            ),
            'curriculum' => array(
                'route' => 'subject/plugin/taxonomy/curriculum',
                'param_provider' => 'Subject\Provider\ParamProvider'
            )
        )
    ),
    'taxonomy' => array(
        'types' => array(
            'topic-folder' => array(
                'allowed_associations' => array(
                    'entities'
                ),
                'allowed_parents' => array(
                    'topic'
                ),
                'rootable' => false
            ),
            'topic' => array(
                'allowed_associations' => array(
                    'entities'
                ),
                'allowed_parents' => array(
                    'abstract-topic'
                ),
                'rootable' => false
            ),
            'abstract-topic' => array(
                'allowed_parents' => array(
                    'subject',
                    'abstract-topic'
                ),
                'rootable' => false
            ),
            'subject' => array(
                'allowed_parents' => array(
                    'root'
                ),
                'rootable' => false
            ),
            'school-type' => array(
                'allowed_parents' => array(
                    'subject',
                    'school-type'
                ),
                'rootable' => false
            ),
            'curriculum' => array(
                'allowed_parents' => array(
                    'school-type'
                ),
                'rootable' => false
            ),
            'curriculum-folder' => array(
                'allowed_associations' => array(
                    'entities'
                ),
                'allowed_parents' => array(
                    'curriculum',
                    'curriculum-folder'
                ),
                'rootable' => false
            )
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view'
        )
    ),
    'router' => array(
        'routes' => array(
            'subject' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                    'may_terminate' => true,
                'options' => array(
                    'route' => '/{subject}/:subject',
                    'defaults' => array(
                        'controller' => __NAMESPACE__ . '\Controller\HomeController',
                        'action' => 'index'
                    )
                ),
                'child_routes' => array(
                    'taxonomy' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '[/:path/]',
                            'defaults' => array(
                                'controller' => __NAMESPACE__ . '\Controller\TaxonomyController',
                                'action' => 'index'
                            ),
                            'constraints' => array(
                                'path' => '(.)+'
                            )
                        )
                    ),
                    'entity' => array(
                        'may_terminate' => true,
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/entity/:action',
                            'defaults' => array(
                                'controller' => __NAMESPACE__ . '\Controller\EntityController',
                                'action' => 'index',
                                'plugin' => 'entity'
                            )
                        )
                    )
                )
            )
        )
    ),
    'di' => array(
        'allowed_controllers' => array(
            __NAMESPACE__ . '\Controller\TaxonomyController',
            __NAMESPACE__ . '\Controller\EntityController',
            __NAMESPACE__ . '\Controller\HomeController'
        ),
        'definition' => array(
            'class' => array(
                __NAMESPACE__ . '\Controller\HomeController' => array(
                    'setLanguageManager' => array(
                        'required' => true
                    ),
                    'setSubjectManager' => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\Controller\TaxonomyController' => array(
                    'setLanguageManager' => array(
                        'required' => true
                    ),
                    'setSubjectManager' => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\Controller\EntityController' => array(
                    'setLanguageManager' => array(
                        'required' => true
                    ),
                    'setSubjectManager' => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\Hydrator\Navigation' => array(
                    'setServiceLocator' => array(
                        'required' => true
                    ),
                    'setSubjectManager' => array(
                        'required' => true
                    ),
                    'setLanguageManager' => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\Manager\SubjectManager' => array(
                    'setTaxonomyManager' => array(
                        'required' => true
                    )
                )
            )
        ),
        'instance' => array(
            'preferences' => array(
                __NAMESPACE__ . '\Manager\SubjectManagerInterface' => __NAMESPACE__ . '\Manager\SubjectManager'
            )
        )
    )
);