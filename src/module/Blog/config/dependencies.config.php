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
use Zend\ServiceManager\ServiceLocatorInterface;
use Blog\Collection\PostCollection;

return [
    'alias_manager' => [
        'aliases' => [
            'blogPost' => [
                'tokenize' => 'blog/{category}/{title}',
                'provider' => 'Blog\Provider\TokenizerProvider',
                'fallback' => 'blog/{category}/{id}-{title}'
            ]
        ]
    ],
    'taxonomy' => [
        'associations' => [
            'blogPosts' => [
                'callback' => function (ServiceLocatorInterface $sm, $collection)
                {
                    return new PostCollection($collection, $sm->get('Blog\Manager\BlogManager'));
                }
            ]
        ],
        'types' => [
            'blog' => [
                'options' => [
                    'allowed_associations' => [
                        'blogPosts'
                    ],
                    'allowed_parents' => [
                        'root'
                    ],
                    'radix_enabled' => false
                ]
            ]
        ]
    ]
];