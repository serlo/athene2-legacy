<?php
return array(
    'navigation' => array(
        'top-right' => array(
            
            array(
                'label' => '',
                'route' => 'user/me',
                'icon' => 'user',
                'needsIdentity' => true
            ),
            array(
                'label' => '',
                'route' => 'user/settings',
                'icon' => 'wrench',
                'needsIdentity' => true
            ),
            array(
                'label' => 'Sign up',
                'route' => 'user/register',
                'needsIdentity' => false
            ),
            array(
                'label' => '',
                'route' => 'user/login',
                'icon' => 'log-in',
                'needsIdentity' => false
            ),
            array(
                'label' => '',
                'route' => 'user/logout',
                'icon' => 'log-out',
                'needsIdentity' => true
            )
        )
    )
);