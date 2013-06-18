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
            array(
                'label' => 'Suchen',
                'uri' => '#'
            ),
            array(
                'label' => 'Prüfungsvorbereitung',
                'uri' => '#'
            ),
            array(
                'label' => 'Lehrplan',
                'uri' => '#'
            ),
            array(
                'label' => 'Lernen',
                'route' => 'subject/math',
                'provider' => 'Taxonomy\Provider\TaxonomyProvider',
                'options' => array(
                    'name' => 'topic',
                    'route' => 'subject/math/topic'
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
                        'label' => 'Aufgaben verwalten',
                        'uri' => '#'
                    ),
                    array(
                        'label' => 'Artikel verwalten',
                        'uri' => '#'
                    ),
                    array(
                        'label' => 'Prüfungen verwalten',
                        'uri' => '#'
                    ),
                    array(
                        'label' => 'Lehrplan verwalten',
                        'uri' => '#'
                    ),
                    array(
                        'label' => 'Themen verwalten',
                        'route' => 'taxonomy/taxonomy',
                        'params' => array(
                            'action' => 'show',
                            'id' => '1'
                        ),
                    ),
                )
            )
        )
    )
);