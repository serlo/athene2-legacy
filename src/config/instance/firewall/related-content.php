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
    'zfcrbac' => array(
        'firewalls' => array(
            'ZfcRbac\Firewall\Route' => array(
                array(
                    'route' => 'related-content/manage',
                    'roles' => 'moderator'
                ),
                array(
                    'route' => 'related-content/add-internal',
                    'roles' => 'moderator'
                ),
                array(
                    'route' => 'related-content/add-external',
                    'roles' => 'moderator'
                ),
                array(
                    'route' => 'related-content/add-category',
                    'roles' => 'moderator'
                ),
                array(
                    'route' => 'related-content/remove',
                    'roles' => 'moderator'
                ),
                array(
                    'route' => 'related-content/order',
                    'roles' => 'moderator'
                ),
            )
        )
    )
);