<?php

return array(
    'zfcrbac' => array(
        'firewalls' => array(
            'ZfcRbac\Firewall\Controller' => array(
                array(
                    'controller' => 'User\Controller\UserController',
                    'actions' => array(
                        'profile',
                        'login',
                        'register',
                        'restorePassword'
                    ),
                    'roles' => 'guest'
                ),
                array(
                    'controller' => 'User\Controller\UserController',
                    'actions' => array(
                        'me',
                        'logout',
                        'settings',
                        'changePassword'
                    ),
                    'roles' => 'login'
                ),
                array(
                    'controller' => 'User\Controller\UserController',
                    'actions' => array(
                        'addRole',
                        'removeRole'
                    ),
                    'roles' => 'sysadmin'
                )
            ),
            'ZfcRbac\Firewall\Route' => array(
                array(
                    'route' => 'user/role/add',
                    'roles' => 'sysadmin'
                )
            )
        )
    )
);