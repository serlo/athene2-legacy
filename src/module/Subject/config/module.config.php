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
        'Subject\Entity\SubjectEntityInterface' => 'Subject\Entity\Subject',
        'Subject\Entity\SubjectTypeInterface' => 'Subject\Entity\SubjectType'
    ),
    'service_manager' => array(
        'factories' => array(
            'Subject\Plugin\PluginManager' => (function ($sm)
            {
                $config = $sm->get('config');
                $config = new \Zend\ServiceManager\Config($config['subject']['plugins']);
                $class = new \Subject\Plugin\PluginManager($config);
                return $class;
            }),
            'Subject\Manager\SubjectManager' => (function ($sm)
            {
                $config = $sm->get('config');
                $class = new \Subject\Manager\SubjectManager($config['subject']);
                
                $class->setPluginManager($sm->get('Subject\Plugin\PluginManager'));
                $class->setServiceLocator($sm->get('ServiceManager'));
                $class->setObjectManager($sm->get('Doctrine\ORM\EntityManager'));
                $class->setClassResolver($sm->get('ClassResolver\ClassResolver'));
                
                return $class;
            })
        )
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