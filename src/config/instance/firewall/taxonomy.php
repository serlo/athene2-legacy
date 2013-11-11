<?php

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