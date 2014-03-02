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

return [
    [
        'label' => 'Permakultur',
        'route' => 'subject',
        'params' => [
            'subject' => 'permakultur'
        ],
        'pages' => [
            [
                'label' => 'Startseite',
                'route' => 'subject',
                'params' => [
                    'subject' => 'permakultur'
                ],
                'icon' => 'home'
            ],
            [
                'label' => 'Lernen',
                'uri' => '#',
                'provider' => 'Taxonomy\Provider\NavigationProvider',
                'options' => [
                    'parent' => [
                        'slug' => 'permakultur',
                        'type' => 'subject'
                    ],
                    'types' => [
                        'topic',
                        'topic-folder'
                    ],
                    'instance' => 'deutsch',
                    'route' => 'subject/taxonomy',
                    'max_depth' => 10,
                    'params' => [
                        'subject' => 'permakultur'
                    ]
                ],
                'icon' => 'book'
            ],
            [
                'label' => 'Verwalten',
                'uri' => '#',
                'pages' => [
                    [
                        'label' => 'Neue Bearbeitungen',
                        'route' => 'subject/entity',
                        'params' => [
                            'subject' => 'permakultur',
                            'action' => 'unrevised'
                        ]
                    ],
                    [
                        'label' => 'Papierkorb',
                        'route' => 'subject/entity',
                        'params' => [
                            'subject' => 'permakultur',
                            'action' => 'trash-bin'
                        ]
                    ],
                    [
                        'label' => 'Taxonomie verwalten',
                        'route' => 'taxonomy/term/organize',
                        'params' => [
                            'id' => '87'
                        ]
                    ]
                ],
                'icon' => 'cog'
            ]
        ]
    ]
];