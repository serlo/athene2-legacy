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
        'label' => 'Physik',
        'uri' => '#',
        'pages' => array(
            /*array(
                'label' => 'Lehrplan',
                'route' => 'subject/plugin/curriculum',
                'params' => array(
                    'subject' => 'physik'
                )
            ),*/
            array(
                'label' => 'Lernen',
                'route' => 'subject',
                'provider' => 'Taxonomy\Provider\NavigationProvider',
                'options' => array(
                    'type' => 'subject',
                    'parent' => 'physik',
                    'language' => 'de',
                    'route' => 'subject/plugin/topic',
                    'params' => array(
                        'subject' => 'physik'
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
                            'subject' => 'physik',
                            'action' => 'get-unrevised'
                        )
                    ),
                    array(
                        'label' => 'Themen verwalten',
                        'route' => 'taxonomy/taxonomy',
                        'params' => array(
                            'action' => 'update',
                            'id' => '26'
                        )
                    )
                )
            )
        )
    )
);