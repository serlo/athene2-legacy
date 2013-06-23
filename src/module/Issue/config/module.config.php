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
namespace Issue;

return array(
    'router' => array(
        'routes' => array(
            'issues' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/issues',
                    'defaults' => array(
                        'controller' => 'Issue\Controller\IssueController',
                        'action' => 'index'
                    )
                ),
            ),
            'issue' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/issues/:action/:id',
                    'defaults' => array(
                        'controller' => 'Issue\Controller\IssueController',
                        'action' => 'index'
                    )
                ),
            )
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view'
        )
    ),
    'di' => array(
        'allowed_controllers' => array(
            'Issue\Controller\IssueController'
        ),
        'definition' => array(
            'class' => array(
                'Issue\Controller\IssueController' => array(
                    'setIssueManager' => array(
                        'required' => 'true'
                    )
                ),
                'Issue\Service\IssueService' => array(),
                'Issue\Manager\IssueManager' => array(
                    'setObjectManager' => array(
                        'required' => 'true'
                    ),
                    'setServiceLocator' => array(
                        'required' => 'true'
                    ),
                    'setAuthService' => array(
                        'required' => 'true'
                    )
                )
            )
        ),
        'instance' => array(
            'preferences' => array(
                'Zend\ServiceManager\ServiceLocatorInterface' => 'ServiceManager',
                'Doctrine\Common\Persistence\ObjectManager' => 'Doctrine\ORM\EntityManager',
                'Issue\Service\IssueServiceInterface' => 'Issue\Service\IssueService',
                'Issue\Manager\IssueManagerInterface' => 'Issue\Manager\IssueManager',
                'Auth\Service\AuthServiceInterface' => 'Auth\Service\AuthService'
            ),
            'Term\Service\TermService' => array(
                'shared' => false
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