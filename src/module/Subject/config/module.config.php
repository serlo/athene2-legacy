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

return array(
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view'
        )
    ),
    'class_resolver' => array(
        'Subject\Service\SubjectServiceInterface' => 'Subject\Service\SubjectService',
        'Subject\Entity\SubjectInterface' => 'Subject\Entity\Subject',
        'Subject\Entity\SubjectTypeInterface' => 'Subject\Entity\SubjectType'
    ),
    'di' => array(
        'allowed_controllers' => array(
            'Subject\Application\DefaultSubject\Controller\TopicController'
        ),
        'definition' => array(
            'class' => array(
                'Subject\Hydrator\RouteStack' => array(
                    'setSubjectManager' => array(
                        'required' => 'true'
                    )
                ),
                'Subject\Hydrator\Route' => array(
                    'setServiceLocator' => array(
                        'required' => 'true'
                    ),
                    'setSubjectManager' => array(
                        'required' => 'true'
                    )
                ),
                'Subject\Hydrator\Navigation' => array(
                    'setServiceLocator' => array(
                        'required' => 'true'
                    ),
                    'setSubjectManager' => array(
                        'required' => 'true'
                    )
                ),
                'Subject\Manager\SubjectManager' => array(
                    'setObjectManager' => array(
                        'required' => 'true'
                    ),
                    'setServiceLocator' => array(
                        'required' => 'true'
                    )
                ),
                'Subject\Service\SubjectService' => array(
                    'setObjectManager' => array(
                        'required' => 'true'
                    ),
                    'setServiceLocator' => array(
                        'required' => 'true'
                    ),
                    'setSubjectManager' => array(
                        'required' => 'true'
                    ),
                    'setEntityManager' => array(
                        'required' => 'true'
                    ),
                    'setSharedTaxonomyManager' => array(
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
                'Subject\Manager\SubjectManagerInterface' => 'Subject\Manager\SubjectManager',
                'Doctrine\Common\Persistence\ObjectManager' => 'Doctrine\ORM\EntityManager'
            )
        )
    ),
    'view_helpers' => array(
        'factories' => array()
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