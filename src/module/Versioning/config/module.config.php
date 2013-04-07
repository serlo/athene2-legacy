<?php
return array(
    'service_manager' => array(
        'factories' => array(
            'Versioning\RepositoryManager' => 'Versioning\RepositoryManager',
            'Versioning\Service\RepositoryService' => function($sm){
                $class = new Versioning\Service\RepositoryService;
                $class->setAuthService($sm->get('Auth\Service\AuthService'));
                $class->setEntityManager($sm->get('EntityManager'));
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
