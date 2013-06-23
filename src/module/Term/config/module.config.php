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
                    'setLanguageManager' => array(
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