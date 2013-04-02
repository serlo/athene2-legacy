<?php
return array(
    'service_manager' => array(
        'factories' => array(
            'Versioning\RepositoryManager' => 'Versioning\RepositoryManager'        
        ),
        'invokables' => array(
            'Versioning\Service\RepositoryService' => 'Versioning\Service\RepositoryService',
        ),
        'shared' => array(
            'Versioning\Service\RepositoryService' => 'false'
        )
    ),
    'di' => array(
        'definition' => array(
            'class' => array(
                'Versioning\Service\RepositoryService' => array(
                    'setEntityManager' => array(
                        'required' => 'true'
                    )
                ),          
            )
        )
    )
);
