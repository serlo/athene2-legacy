<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace User;

return array(
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view'
        )
    ),
    'class_resolver' => array(
        'User\Entity\UserInterface' => 'User\Entity\User',
        'User\Service\UserServiceInterface' => 'User\Service\UserService'
    ),
    'di' => array(
        'definition' => array(
            'class' => array(
                'User\Manager\UserManager' => array(
                    'setClassResolver' => array(
                        'required' => 'true'
                    ),
                    'setServiceLocator' => array(
                        'required' => 'true'
                    ),
                    'setObjectManager' => array(
                        'required' => 'true'
                    )
                )
            )
        ),
        'instance' => array(
            'preferences' => array(
                'Zend\ServiceManager\ServiceLocatorInterface' => 'ServiceManager',
                'Auth\Service\AuthServiceInterface' => 'Auth\Service\AuthService'
            ),
            'User\Service\UserService' => array(
                'shared' => false
            )
        )
    ),
    'service_manager' => array(
        'factories' => array(
            'User\Service\UserLogService' => function  ($sm)
            {
                $srv = new Service\UserLogService();
                $srv->setEntityManager($sm->get('EntityManager'));
                $srv->setAuthService($sm->get('Auth\Service\AuthService'));
                return $srv;
            },
            'User\Service\UserService' => function  ($sm)
            {
                $srv = new Service\UserService();
                $srv->setObjectManager($sm->get('EntityManager'));
                return $srv;
            }
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