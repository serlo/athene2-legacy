<?php

return array(
    'zfcrbac' => array(
        'firewalls' => array(
            'ZfcRbac\Firewall\Route' => array(
                array(
                    'route' => 'blog/post/create',
                    'roles' => 'admin'
                ),
                array(
                    'route' => 'blog/view-all',
                    'roles' => 'admin'
                ),
                array(
                    'route' => 'blog/post/update',
                    'roles' => 'admin'
                ),
                array(
                    'route' => 'blog/post/trash',
                    'roles' => 'admin'
                ),
            )
        )
    )
);