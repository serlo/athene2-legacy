<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
return array(
    'navigation' => array(
        'top-right' => array(

            array(
                'label'         => '',
                'route'         => 'user/me',
                'icon'          => 'user',
                'needsIdentity' => true
            ),
            array(
                'label'         => '',
                'route'         => 'user/settings',
                'icon'          => 'wrench',
                'needsIdentity' => true
            ),
            array(
                'label'         => 'Sign up',
                'route'         => 'user/register',
                'needsIdentity' => false
            ),
            array(
                'label'         => '',
                'route'         => 'authentication/login',
                'icon'          => 'log-in',
                'needsIdentity' => false
            ),
            array(
                'label'         => '',
                'route'         => 'authentication/logout',
                'icon'          => 'log-out',
                'needsIdentity' => true
            )
        )
    )
);