<?php
/**
 * 
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Link;

return array(
    'class_resolver' => array(
        'Link\Service\LinkServiceInterface' => 'Link\Service\LinkService',
        'Link\Manager\LinkManagerInterface' => 'Link\Manager\LinkManager'
    ),
    'di' => array(
        'definition' => array(
            'class' => array(
                'Link\Service\LinkService' => array(
                    'setEntityManager' => array(
                        'required' => 'true'
                    ),
                    'setObjectManager' => array(
                        'required' => 'true'
                    )
                ),
                'Link\Manager\LinkManager' => array(
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
                'Link\Manager\SharedLinkManager' => array(
                    'setServiceLocator' => array(
                        'required' => 'true'
                    ),
                    'setObjectManager' => array(
                        'required' => 'true'
                    ),
                    'setClassResolver' => array(
                        'required' => 'true'
                    ),
                    'setSharedLinkManager' => array(
                        'required' => 'true'
                    )
                )
            )
        ),
        'instance' => array(
            'preferences' => array(
                'Link\Manager\SharedLinkManagerInterface' => 'Link\Manager\SharedLinkManager',
                'Zend\ServiceManager\ServiceLocatorInterface' => 'ServiceManager',
                'ClassResolver\ClassResolverInterface' => 'ClassResolver\ClassResolver'
            ),
            'Link\Manage\LinkManager' => array(
                'shared' => false
            ),
            'Link\Service\LinkService' => array(
                'shared' => false
            )
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view'
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

