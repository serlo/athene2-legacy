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
        'uri' => '#',
        'pages' => array(
            /*array(
                'label' => 'Lehrplan',
                'uri' => 'subject',
                'provider' => 'Taxonomy\Provider\NavigationProvider',
                'options' => array(
                    'name' => 'curriculum',
                    'route' => 'subject/plugin/curriculum',
                    'params' => array(
                        'subject' => 'math'
                    )
                )
            ),*/
            array(
                'label' => 'Lernen',
                'route' => 'subject',
                'provider' => 'Taxonomy\Provider\NavigationProvider',
                'options' => array(
                    'type' => 'topic',
                    'parent' => 'mathe',
                    'language' => 'de',
                    'route' => 'subject/plugin/topic',
                    'params' => array(
                        'subject' => 'mathe'
                    )
                )
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
                    )
                )
            )
        )
    ),
    array(
        'label' => 'Mitmachen',
        'uri' => '#',
        'pages' => array(
            array(
                'label' => 'Mathe',
                'uri' => '#',
                'pages' => array(
                    array(
                        'label' => 'Themen verwalten',
                        'route' => 'taxonomy/taxonomy',
                        'params' => array(
                            'action' => 'show',
                            'id' => '1'
                        )
                    )
                )
            )
        )
    )
);