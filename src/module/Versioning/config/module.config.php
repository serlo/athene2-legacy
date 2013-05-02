<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
return array(
    'service_manager' => array(
        'factories' => array(
            'Versioning\RepositoryManager' => 'Versioning\RepositoryManager',
            'Versioning\Service\RepositoryService' => function  ($sm)
            {
                $class = new Versioning\Service\RepositoryService();
                $class->setAuthService($sm->get('Auth\Service\AuthService'));
                $class->setEntityManager($sm->get('EntityManager'));
                $class->setEventManager($sm->get('EventManager'));
                
                $sm->get('Log\Service\LogManager')
                    ->get('userLog')
                    ->LogOn($class->getEventManager(), 'Versioning\Service\RepositoryService', array(
                    'checkoutRevision',
                    'addRevision',
                    'trashRevision',
                    'deleteRevision'
                ));
                
                return $class;
            }
        ),
        'shared' => array(
            'Versioning\Service\RepositoryService' => 'false'
        )
    )
);
