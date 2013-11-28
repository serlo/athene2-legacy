<?php
/**
 * 
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Metadata;

return array(
    'class_resolver' => array(
        __NAMESPACE__ . '\Entity\MetadataInterface' => __NAMESPACE__ . '\Entity\Metadata',
        __NAMESPACE__ . '\Entity\MetadataKeyInterface' => __NAMESPACE__ . '\Entity\MetadataKey'
    ),
    'di' => array(
        'definition' => array(
            'class' => array(
                __NAMESPACE__ . '\Manager\MetadataManager' => array(
                    'setServiceLocator' => array(
                        'required' => 'true'
                    ),
                    'setObjectManager' => array(
                        'required' => 'true'
                    ),
                    'setClassResolver' => array(
                        'required' => 'true'
                    )
                ),
                __NAMESPACE__ . '\Listener\EntityControllerListener' => array(
                    'setMetadataManager' => array(
                        'required' => 'true'
                    ),
                ),
                __NAMESPACE__ . '\Listener\EntityTaxonomyPluginControllerListener' => array(
                    'setMetadataManager' => array(
                        'required' => 'true'
                    ),
                )
            )
        ),
        'instance' => array(
            'preferences' => array(
                __NAMESPACE__ . '\Manager\MetadataManagerInterface' => __NAMESPACE__ . '\Manager\MetadataManager'
            )
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