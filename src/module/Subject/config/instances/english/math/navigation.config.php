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
return [
    [
        'label' => 'Math',
        'route' => 'subject',
        'params' => [
            'subject' => 'math'
        ],
        'pages' => [
            [
                'label' => 'Home',
                'route' => 'subject',
                'params' => [
                    'subject' => 'math'
                ],
                'icon' => 'home'
            ],
            [
                'label' => 'Learn',
                'route' => 'subject/plugin/taxonomy/topic',
                'params' => [
                    'subject' => 'math'
                ],
                'provider' => 'Taxonomy\Provider\NavigationProvider',
                'options' => [
                    'parent' => [
                        'name' => 'math',
                        'type' => 'subject'
                    ],
                    'types' => [
                        'topic'
                    ],
                    'instance' => 'english',
                    'route' => 'subject/plugin/taxonomy/topic',
                    'max_depth' => 10,
                    'params' => [
                        'subject' => 'math'
                    ]
                ],
                'icon' => 'book'
            ]
        ]
    ]
];