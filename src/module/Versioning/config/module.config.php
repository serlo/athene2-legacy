<?php
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
                
                $sm->get('Log\Service\LogManager')->get('userLog')->LogOn($class->getEventManager(), 'Versioning\Service\RepositoryService', array(
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
    ),
    /*'di' => array(
        'definition' => array(
            'class' => array(
                'Versioning\Service\RepositoryService' => array(
                    'setEntityManager' => array(
                        'required' => 'true'
                    ),
                    'setAuthService' => array(
                        'required' => 'true'
                    )
                )
            ),
            'instance' => array(
                'preferences' => array(
                    'Auth\Service\AuthServiceInterface' => 'Auth\Service\AuthService'
                )
            )
        )
    )*/
);
