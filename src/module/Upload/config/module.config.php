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

use Zend\ServiceManager\ServiceLocatorInterface;
use Upload\Manager\UploadManager;
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
                $instance->setObjectManager($sl->get('EntityManager'));
                $instance->setServiceLocator($sl);
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
                        'required' => 'true'
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
    'router' => array(
        'routes' => array(
            'upload' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/upload',
                    'defaults' => array(
                        'controller' => 'Upload\Controller\UploadController',
                        'action' => 'upload'
                    )
                ),
            )
        )
    )
);