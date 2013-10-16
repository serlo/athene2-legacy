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
        'route' => 'subject',
        'params' => array(
            'subject' => 'physik'
        ),
        'pages' => array(
            array(
                'label' => 'Lernen',
                'route' => 'subject/plugin/taxonomy/topic',
                'params' => array(
                    'subject' => 'physik'
                ),
                'provider' => 'Taxonomy\Provider\NavigationProvider',
                'options' => array(
                    'type' => 'subject',
                    'parent' => 'physik',
                    'language' => 'de',
                    'route' => 'subject/plugin/taxonomy/topic',
                    'max_depth' => 10,
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
                        'route' => 'restricted/taxonomy/taxonomy',
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