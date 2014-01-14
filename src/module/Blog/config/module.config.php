<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author	Aeneas Rekkas (aeneas.rekkas@serlo.org]
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c] 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/]
 */
namespace Blog;

return [
    'zfc_rbac' => [
        'assertion_map' => [
            'blog.post.create' => 'Authorization\Assertion\LanguageAssertion',
            'blog.post.update' => 'Authorization\Assertion\LanguageAssertion',
            'blog.post.trash' => 'Authorization\Assertion\LanguageAssertion',
            'blog.post.delete' => 'Authorization\Assertion\LanguageAssertion',
            'blog.posts.view_all' => 'Authorization\Assertion\LanguageAssertion'
        ]
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity'
                ]
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ],
    'di' => [
        'allowed_controllers' => [
            __NAMESPACE__ . '\Controller\BlogController'
        ],
        'definition' => [
            'class' => [
                __NAMESPACE__ . '\Controller\BlogController' => [
                    'setBlogManager' => [
                        'required' => true
                    ],
                    'setUserManager' => [
                        'required' => true
                    ],
                    'setLanguageManager' => [
                        'required' => true
                    ]
                ],
                __NAMESPACE__ . '\Manager\BlogManager' => [
                    'setTaxonomyManager' => [
                        'required' => true
                    ],
                    'setServiceLocator' => [
                        'required' => true
                    ],
                    'setClassResolver' => [
                        'required' => true
                    ],
                    'setObjectManager' => [
                        'required' => true
                    ],
                    'setUuidManager' => [
                        'required' => true
                    ],
                    'setLanguageManager' => [
                        'required' => true
                    ],
                    'setAuthorizationService' => [
                        'required' => true
                    ]
                ]
            ]
        ],
        'instance' => [
            'preferences' => [
                __NAMESPACE__ . '\Manager\BlogManagerInterface' => __NAMESPACE__ . '\Manager\BlogManager'
            ]
        ]
    ],
    'class_resolver' => [
        __NAMESPACE__ . '\Entity\PostInterface' => __NAMESPACE__ . '\Entity\Post',
        __NAMESPACE__ . '\Service\PostServiceInterface' => __NAMESPACE__ . '\Service\PostService',
        __NAMESPACE__ . '\Manager\PostManagerInterface' => __NAMESPACE__ . '\Manager\PostManager'
    ]
];
