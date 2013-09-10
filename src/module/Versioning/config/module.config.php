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
                $instance = new Versioning\Service\RepositoryService();
                $instance->setAuthService($sm->get('Auth\Service\AuthService'));
                $instance->setEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                $instance->setEventManager($sm->get('EventManager'));
                
                $sm->get('Log\Service\LogManager')
                    ->get('userLog')
                    ->LogOn($instance->getEventManager(), 'Versioning\Service\RepositoryService', array(
                    'checkoutRevision',
                    'addRevision',
                    'removeRevision'
                ));
                
                return $instance;
            }
        ),
        'shared' => array(
            'Versioning\Service\RepositoryService' => 'false'
        )
    )
);
