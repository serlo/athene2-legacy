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
namespace Blog;

use Zend\ServiceManager\ServiceLocatorInterface;
return array(
    'taxonomy' => array(
        'associations' => array(
            'blogs' => function (ServiceLocatorInterface $sm, $collection)
            {
                return new $collection();
            }
        ),
        'types' => array(
            'blog' => array(
                'options' => array(
                    'allowed_associations' => array(
                        'entities'
                    ),
                    'allowed_parents' => array(
                        'root'
                    ),
                    'radix_enabled' => false
                )
            ),
            'blog-category' => array(
                'options' => array(
                    'allowed_associations' => array(
                        'blogs'
                    ),
                    'allowed_parents' => array(
                        'blog'
                    ),
                    'radix_enabled' => false
                )
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
    ),
    'di' => array(
        'definition' => array(
            'class' => array(
                __NAMESPACE__ . '\Manager\BlogManager' => array(
                    'setSharedTaxonomyManager' => array(
                        'required' => true
                    ),
                    'setServiceLocator' => array(
                        'required' => true
                    ),
                    'setClassResolver' => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\Manager\PostManager' => array(
                    'setObjectManager' => array(
                        'required' => true
                    ),
                    'setServiceLocator' => array(
                        'required' => true
                    ),
                    'setClassResolver' => array(
                        'required' => true
                    )
                ),
                __NAMESPACE__ . '\Service\PostService' => array()
            )
        ),
        'instance' => array(
            'preferences' => array(
                __NAMESPACE__ . '\Manager\PostManagerInterface' => __NAMESPACE__ . '\Manager\PostManager',
                __NAMESPACE__ . '\Manager\BlogManagerInterface' => __NAMESPACE__ . '\Manager\BlogManager'
            ),
            __NAMESPACE__ . '\Service\PostService' => array(
                'shared' => false
            )
        )
    ),
    'class_resolver' => array(
        __NAMESPACE__ . '\Entity\PostInterface' => __NAMESPACE__ . '\Entity\Post',
        __NAMESPACE__ . '\Service\PostServiceInterface' => __NAMESPACE__ . '\Service\PostService',
        __NAMESPACE__ . '\Manager\PostManagerInterface' => __NAMESPACE__ . '\Manager\PostManager'
    )
);