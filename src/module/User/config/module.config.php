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
    'service_manager' => array(
        'factories' => array(
            'User\Service\UserLogService' => function ($sm){
                $srv = new Service\UserLogService();
                $srv->setEntityManager($sm->get('EntityManager'));
                return $srv;
            },
            'User\Service\UserService' => function ($sm)
            {
                $srv = new Service\UserService();
                $srv->setEntityManager($sm->get('EntityManager'));
                return $srv;
            }
        ),      
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