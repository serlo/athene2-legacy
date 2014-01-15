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
namespace Upload;

use Upload\Manager\UploadManager;
use Zend\ServiceManager\ServiceLocatorInterface;

return array(
    'class_resolver' => array(
        'Upload\Entity\UploadInterface' => 'Upload\Entity\Upload'
    ),
    'upload_manager' => array(),
    'service_manager' => array(
        'factories' => array(
            'Upload\Manager\UploadManager' => function (ServiceLocatorInterface $sl)
            {
                $instance = new UploadManager();
                $config = $sl->get('config')['upload_manager'];
                $instance->setClassResolver($sl->get('ClassResolver\ClassResolver'));
                $instance->setConfig($config);
                $instance->setObjectManager($sl->get('Doctrine\ORM\EntityManager'));
                //$instance->setServiceLocator($sl);
                $instance->setUuidManager($sl->get('Uuid\Manager\UuidManager'));
                $instance->setLanguageManager($sl->get('Language\Manager\LanguageManager'));
                return $instance;
            }
        )
    ),
    'di' => array(
        'allowed_controllers' => array(
            'Upload\Controller\UploadController',
            'Taxonomy\Controller\TaxonomyController'
        ),
        'definition' => array(
            'class' => array(
                'Upload\Controller\UploadController' => array(
                    'setUploadManager' => array(
                        'required' => true
                    )
                ),
            )
        ),
        'instance' => array(
            'preferences' => array(
                'Upload\Manager\UploadManagerInterface' => 'Upload\Manager\UploadManager'
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
    ),
    'router' => array(
        'routes' => array(
            'upload' => array(
                'type' => 'Segment',
                'may_terminate' => true,
                'options' => array(
                    'route' => '/upload',
                    'defaults' => array(
                        'controller' => 'Upload\Controller\UploadController',
                        'action' => 'upload'
                    )
                ),
                'child_routes' => array(
                    'get' => array(
                        'type' => 'Segment',
                        'may_terminate' => true,
                        'options' => array(
                            'route' => '/get/:id',
                            'defaults' => array(
                                'controller' => 'Upload\Controller\UploadController',
                                'action' => 'get'
                            )
                        ),
                    )
                )
            ),
        )
    )
);