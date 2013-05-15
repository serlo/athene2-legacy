<?php
namespace Term;

return array(
    'di' => array(
        'definition' => array(
            'class' => array(
                'Term\Manager\TermManager' => array(
                    'setObjectManager' => array(
                        'required' => 'true'
                    ),
                    'setServiceLocator' => array(
                        'required' => 'true'
                    ),
                ),
                'Term\Service\TermService' => array(
                    'setObjectManager' => array(
                        'required' => 'true'
                    ),
                    'setServiceLocator' => array(
                        'required' => 'true'
                    ),
                ),
            )
        ),
        'instance' => array(
            'preferences' => array(
                'Zend\ServiceManager\ServiceLocatorInterface' => 'ServiceManager',
                'Doctrine\Common\Persistence\ObjectManager' => 'Doctrine\ORM\EntityManager',
                'Term\Service\TermServiceInterface' => 'Term\Service\TermService',
            ),
            'Term\Service\TermService' => array(
                'shared' => false
            ),
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