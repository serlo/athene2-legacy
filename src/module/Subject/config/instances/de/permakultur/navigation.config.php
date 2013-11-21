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
return array(
    array(
        'label' => 'Permakultur',
        'route' => 'subject',
        'params' => array(
            'subject' => 'permakultur'
        ),
        'pages' => array(
            array(
                'label' => 'Startseite',
                'route' => 'subject',
                'params' => array(
                    'subject' => 'permakultur'
                ),
                'icon' => 'home'
            ),
            array(
                'label' => 'Lernen',
                'route' => 'subject/plugin/taxonomy/topic',
                'params' => array(
                    'subject' => 'permakultur'
                ),
                'provider' => 'Taxonomy\Provider\NavigationProvider',
                'options' => array(
                    'parent' => array(
                        'slug' => 'permakultur',
                        'type' => 'subject'
                    ),
                    'types' => array(
                        'abstract-topic',
                        'topic'
                    ),
                    'language' => 'de',
                    'route' => 'subject/plugin/taxonomy/topic',
                    'max_depth' => 10,
                    'params' => array(
                        'subject' => 'permakultur'
                    )
                ),
                'icon' => 'book'
            ),
            array(
                'label' => 'Verwalten',
                'uri' => '#',
                'pages' => array(
                    array(
                        'label' => 'Neue Bearbeitungen',
                        'route' => 'subject/plugin/entity',
                        'params' => array(
                            'subject' => 'permakultur',
                            'action' => 'unrevised'
                        )
                    ),
                    array(
                        'label' => 'Papierkorb',
                        'route' => 'subject/plugin/entity',
                        'params' => array(
                            'subject' => 'permakultur',
                            'action' => 'trash-bin'
                        )
                    ),
                    array(
                        'label' => 'Taxonomie verwalten',
                        'route' => 'taxonomy/term/organize',
                        'params' => array(
                            'id' => '87'
                        )
                    )
                ),
                'icon' => 'cog'
            )
        )
    )
);