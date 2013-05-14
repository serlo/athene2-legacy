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
namespace Subject\Math;

return array(
    'navigation' => array(
        'default' => array(
            array(
                'label' => 'Mathe',
                'uri' => '#',
                'pages' => array(
                    array(
                        'label' => 'Startseite',
                        'route' => 'math'
                    ),
                    array(
                        'label' => 'Themen',
                        'route' => 'math',
                        'provider' => 'Navigation\Provider\TaxonomyProvider',
                        'options' => array(
                            'name' => 'topic',
                            'ancestor' => 'math',
                            'route' => 'math/topic'
                        )
                    )
                )
            )
        )
    ),
    'di' => array(
        'allowed_controllers' => array(
            'Subject\Math\Controller\TopicController'
        ),
        'definition' => array(
            'class' => array(
                'Subject\Math\Controller\TopicController' => array(
                    'setSharedTaxonomyManager' => array(
                        'required' => 'true'
                    ),
                    'setEntityManager' => array(
                        'required' => 'true'
                    )
                )
            )
        ),
        'instance' => array(
            'preferences' => array(
                'Entity\EntityManagerInterface' => 'Entity\EntityManager',
                'Zend\ServiceManager\ServiceLocatorInterface' => 'ServiceManager',
                'Taxonomy\SharedTaxonomyManagerInterface' => 'Taxonomy\SharedTaxonomyManager',
                'Subject\Core\SubjectManagerInterface' => 'Subject\Core\SubjectManager',
                'Doctrine\Common\Persistence\ObjectManager' => 'Doctrine\ORM\EntityManager',
            )
        )
    ),
    'router' => array(
        'routes' => array(
            'math' => array(
                'may_terminate' => true,
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/math[/]',
                    'defaults' => array(
                        'controller' => 'Subject\Math\Controller\IndexController',
                        'action' => 'index'
                    )
                ),
                'child_routes' => array(
                    'topic' => array(
                        'may_terminate' => true,
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => 'topic/:path',
                            'defaults' => array(
                                'controller' => 'Subject\Math\Controller\TopicController',
                                'action' => 'index'
                            ),
                            'constraints' => array(
                                'path' => '(.)+'
                            )
                        )
                    )
                )
            )
        )
    )
);



