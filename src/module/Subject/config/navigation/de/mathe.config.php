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
        'label' => 'Mathe',
        'route' => 'subject',
        'params' => array(
            'subject' => 'mathe'
        ),
        'pages' => array(
            array(
                'label' => 'Lehrplan',
                'route' => 'subject/plugin/taxonomy/curriculum',
                'params' => array(
                    'subject' => 'mathe'
                ),
                'icon' => 'map-marker'
            ),
            array(
                'label' => 'Lernen',
                'route' => 'subject/plugin/taxonomy/topic',
                'params' => array(
                    'subject' => 'mathe'
                ),
                'provider' => 'Taxonomy\Provider\NavigationProvider',
                'options' => array(
                    'type' => 'subject',
                    'parent' => 'mathe',
                    'language' => 'de',
                    'route' => 'subject/plugin/taxonomy/topic',
                    'max_depth' => 10,
                    'parent_type' => 'subject',
                    'params' => array(
                        'subject' => 'mathe'
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
                            'subject' => 'mathe',
                            'action' => 'get-unrevised'
                        )
                    ),
                    array(
                        'label' => 'Taxonomie verwalten',
                        'route' => 'restricted/taxonomy/taxonomy',
                        'params' => array(
                            'action' => 'update',
                            'id' => '25'
                        )
                    )
                ),
                'icon' => 'cog'
            )
        )
    )
);