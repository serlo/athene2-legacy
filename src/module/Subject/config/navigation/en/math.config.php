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
        'uri' => '#',
        'pages' => array(
            array(
                'label' => 'Learn',
                'route' => 'subject',
                'provider' => 'Taxonomy\Provider\NavigationProvider',
                'options' => array(
                    'type' => 'topic',
                    'parent' => '',
                    'language' => 'en',
                    'route' => 'subject/plugin/topic',
                    'params' => array(
                        'subject' => 'math'
                    )
                )
            )
        )
    )
);