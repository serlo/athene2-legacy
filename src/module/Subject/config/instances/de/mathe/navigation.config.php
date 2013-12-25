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
        'label' => 'Mathe',
        'route' => 'subject',
        'params' => [
            'subject' => 'mathe'
        ],
        'pages' => [
            [
                'label' => 'Startseite',
                'route' => 'subject',
                'params' => [
                    'subject' => 'mathe'
                ],
                'icon' => 'home'
            ],
            [
                'label' => 'Lehrplan',
                'route' => 'subject/taxonomy',
                'params' => [
                    'subject' => 'mathe'
                ],
                'provider' => 'Taxonomy\Provider\NavigationProvider',
                'options' => [
                    'parent' => [
                        'slug' => 'mathe',
                        'type' => 'subject'
                    ],
                    'types' => [
                        'school-type',
                        'curriculum'
                    ],
                    'language' => 'de',
                    'route' => 'subject/taxonomy',
                    'max_depth' => 10,
                    'params' => [
                        'subject' => 'mathe'
                    ]
                ],
                'icon' => 'map-marker'
            ],
            [
                'label' => 'Lernen',
                'route' => 'subject/taxonomy',
                'params' => [
                    'subject' => 'mathe'
                ],
                'provider' => 'Taxonomy\Provider\NavigationProvider',
                'options' => [
                    'parent' => [
                        'slug' => 'mathe',
                        'type' => 'subject'
                    ],
                    'types' => [
                        'abstract-topic',
                        'topic'
                    ],
                    'language' => 'de',
                    'route' => 'subject/taxonomy',
                    'max_depth' => 10,
                    'params' => [
                        'subject' => 'mathe'
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
                            'subject' => 'mathe',
                            'action' => 'unrevised'
                        ]
                    ],
                    [
                        'label' => 'Papierkorb',
                        'route' => 'subject/entity',
                        'params' => [
                            'subject' => 'mathe',
                            'action' => 'trash-bin'
                        ]
                    ],
                    [
                        'label' => 'Taxonomie verwalten',
                        'route' => 'taxonomy/term/organize',
                        'params' => [
                            'id' => '5'
                        ]
                    ]
                ],
                'icon' => 'cog'
            ]
        ]
    ]
];