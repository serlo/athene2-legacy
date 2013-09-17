<?php
/**
 * ZfcRbac Configuration
 *
 * If you have a ./config/autoload/ directory set up for your project, you can
 * drop this config file in it and change the values as you wish.
 */
$settings = array(
    /**
     * The default role that is used if no role is found from the
     * role provider.
     */
    'anonymousRole' => 'guest',

    /**
     * Flag: enable or disable the routing firewall.
     */
    'firewallRoute' => true,

    /**
     * Flag: enable or disable the controller firewall.
     */
    'firewallController' => true,

    /**
     * Set the view template to use on a 403 error.
     */
    'template' => 'error/403',

    /**
     * flag: enable or disable the use of lazy-loading providers.
     */
    'enableLazyProviders' => true,

    'firewalls' => array(
        'ZfcRbac\Firewall\Route' => array(
        ),
    ),
    'providers' => array(
        'ZfcRbac\Provider\AdjacencyList\Role\DoctrineDbal' => array(
            'connection' => 'doctrine.connection.orm_default',
            'options' => array(
                'table' => 'role',
                'id_column' => 'id',
                'name_column' => 'name',
                'join_column' => 'parent_id'
            )
        ),
        'ZfcRbac\Provider\Generic\Permission\DoctrineDbal' => array(
            'connection' => 'doctrine.connection.orm_default',
            'options' => array(
                'permission_table' => 'permission',
                'role_table' => 'role',
                'role_join_table' => 'role_permission',
                'permission_id_column' => 'id',
                'permission_join_column' => 'permission_id',
                'role_id_column' => 'id',
                'role_join_column' => 'role_id',
                'permission_name_column' => 'name',
                'role_name_column' => 'name'
            )
        )
    ),

    /**
     * Set the identity provider to use. The identity provider must be retrievable from the
     * service locator and must implement \ZfcRbac\Identity\IdentityInterface.
     */
    'identity_provider' => 'standard_identity'
);

$serviceManager = array(
    'factories' => array(
        'standard_identity' => function ($sm) {
                $user = $sm->get('User\Manager\UserManager')->getUserFromAuthenticator();
                $ls = $sm->get('Language\Manager\LanguageManager')->getLanguageFromRequest();
                
                if(!$user){
                    return new \ZfcRbac\Identity\StandardIdentity('guest');
                } else {
                    $identity = new \ZfcRbac\Identity\StandardIdentity($user->getRoleNames($ls));
                    return $identity;
                }
        },
    )
);

/**
 * You do not need to edit below this line
 */
return array(
    'zfcrbac' => $settings,
    'service_manager' => $serviceManager,
);
