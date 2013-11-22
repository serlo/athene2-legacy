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
            'ZfcRbac\Firewall\Controller' => array(
                array(
                    'controller' => 'Taxonomy\Controller\TaxonomyController',
                    'actions' => array(),
                    'roles' => 'moderator'
                ),
                array(
                    'controller' => 'Taxonomy\Controller\TermController',
                    'actions' => array(
                        'update',
                        'delete',
                        'order',
                        'create',
                        'orderAssociated',
                        'organize'
                    ),
                    'roles' => 'moderator'
                ),
            ),
            'ZfcRbac\Firewall\Route' => array(
                array(
                    'route' => 'taxonomy/term/sort-associated',
                    'roles' => 'moderator'
                ),
                array(
                    'route' => 'taxonomy/term/order',
                    'roles' => 'moderator'
                ),
            )
        )
    )
);