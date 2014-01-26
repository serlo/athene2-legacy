<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Attachment;

use Attachment\Manager\AttachmentManager;
use Zend\ServiceManager\ServiceLocatorInterface;

return array(
    'class_resolver'  => array(
        'Attachment\Entity\ContainerInterface' => 'Attachment\Entity\Container',
        'Attachment\Entity\FileInterface'       => 'Attachment\Entity\File'
    ),
    'upload_manager'  => array(),
    'service_manager' => array(
        'factories' => array(
            'Attachment\Manager\AttachmentManager' => function (ServiceLocatorInterface $sl) {
                    $instance = new AttachmentManager();
                    $config   = $sl->get('config')['upload_manager'];
                    $instance->setClassResolver($sl->get('ClassResolver\ClassResolver'));
                    $instance->setConfig($config);
                    $instance->setObjectManager($sl->get('Doctrine\ORM\EntityManager'));
                    $instance->setUuidManager($sl->get('Uuid\Manager\UuidManager'));
                    $instance->setLanguageManager($sl->get('Language\Manager\LanguageManager'));
                    $instance->setTypeManager($sl->get('Type\TypeManager'));

                    return $instance;
                }
        )
    ),
    'di'              => array(
        'allowed_controllers' => array(
            'Attachment\Controller\AttachmentController',
            'Taxonomy\Controller\TaxonomyController'
        ),
        'definition'          => array(
            'class' => array(
                'Attachment\Controller\AttachmentController' => array(
                    'setAttachmentManager' => array(
                        'required' => true
                    )
                ),
            )
        ),
        'instance'            => array(
            'preferences' => array(
                'Attachment\Manager\AttachmentManagerInterface' => 'Attachment\Manager\AttachmentManager'
            ),
        )
    ),
    'doctrine'        => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity'
                )
            ),
            'orm_default'             => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        )
    ),
    'router'          => array(
        'routes' => array(
            'attachment' => array(
                'type'         => 'Segment',
                'options'      => array(
                    'route'      => '/attachment',
                    'defaults' => array(
                        'controller' => 'Attachment\Controller\AttachmentController',
                    )
                ),
                'child_routes' => array(
                    'info'   => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/info/:id',
                            'defaults' => array(
                                'action' => 'info'
                            )
                        ),
                    ),
                    'file'   => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/file/:id[/:file]',
                            'defaults' => array(
                                'action' => 'file'
                            )
                        ),
                    ),
                    'upload' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/upload[/:append]',
                            'defaults' => array(
                                'action' => 'attach'
                            )
                        ),
                    )
                )
            ),
        )
    )
);
