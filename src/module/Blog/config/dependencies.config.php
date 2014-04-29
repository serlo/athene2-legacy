<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Blog;

return [
    'alias_manager' => [
        'aliases' => [
            'blogPost' => [
                'tokenize' => 'blog/{blog}/{title}',
                'provider' => 'Blog\Provider\TokenizerProvider',
                'fallback' => 'blog/{blog}/{title}-{id}'
            ]
        ]
    ],
    'taxonomy'      => [
        'types' => [
            'blog' => [
                'allowed_associations' => [
                    'Blog\Entity\PostInterface'
                ],
                'allowed_parents'      => [
                    'root'
                ],
                'rootable'             => false
            ]
        ]
    ]
];
