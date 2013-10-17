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
        'label' => 'Math',
        'route' => 'subject',
        'params' => array(
            'subject' => 'math'
        ),
        'pages' => array(
            array(
                'label' => 'Home',
                'route' => 'subject',
                'params' => array(
                    'subject' => 'math'
                ),
                'icon' => 'home'
            ),
            array(
                'label' => 'Learn',
                'route' => 'subject/plugin/taxonomy/topic',
                'params' => array(
                    'subject' => 'math'
                ),
                'provider' => 'Taxonomy\Provider\NavigationProvider',
                'options' => array(
                    'type' => 'subject',
                    'parent' => 'math',
                    'language' => 'en',
                    'route' => 'subject/plugin/taxonomy/topic',
                    'max_depth' => 10,
                    'params' => array(
                        'subject' => 'math'
                    )
                ),
                'icon' => 'book'
            )
        )
    )
);