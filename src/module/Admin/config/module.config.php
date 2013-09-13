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
namespace Admin;

return array(
    'di' => array(
        'allowed_controllers' => array(
            __NAMESPACE__ . '\Controller\UserController',
        ),
        'definition' => array(
            'class' => array(
                __NAMESPACE__ . '\Controller\UserController' => array(
                    'setAuthService' => array(
                        'required' => 'true'
                    ),
                    'setUserManager' => array(
                        'required' => 'true'
                    ),
                )
            )
        ),
        'instance' => array(
            'preferences' => array(),
            'alias' => array()
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view'
        )
    )
);