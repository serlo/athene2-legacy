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
        'label' => 'Physik',
        'route' => 'subject',
        'params' => [
            'subject' => 'physik'
        ],
        'pages' => [
            [
                'label' => 'Startseite',
                'route' => 'subject',
                'params' => [
                    'subject' => 'physik'
                ],
                'icon' => 'home'
            ],
            [
                'label' => 'Lernen',
                'route' => 'subject/taxonomy',
                'params' => [
                    'subject' => 'physik'
                ],
                'provider' => 'Taxonomy\Provider\NavigationProvider',
                'options' => [
                    'parent' => [
                        'slug' => 'physik',
                        'type' => 'subject'
                    ],
                    'types' => [
                        'abstract-topic',
                        'topic'
                    ],
                    'instance' => 'deutsch',
                    'route' => 'subject/taxonomy',
                    'max_depth' => 10,
                    'params' => [
                        'subject' => 'physik'
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
                            'subject' => 'physik',
                            'action' => 'unrevised'
                        ]
                    ],
                    [
                        'label' => 'Themen verwalten',
                        'route' => 'taxonomy/term/organize',
                        'params' => [
                            'id' => '6'
                        ]
                    ]
                ],
                'icon' => 'cog'
            ]
        ]
    ]
];
