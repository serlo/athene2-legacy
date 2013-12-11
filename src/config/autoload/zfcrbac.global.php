<?php
use ZfcRbac\Guard\GuardInterface;

return [
    'zfc_rbac' => [
        'role_provider' => [
            'ZfcRbac\Role\ObjectRepositoryRoleProvider' => [
                'object_manager' => 'doctrine.entitymanager.orm_default',
                'class_name' => 'User\Entity\Role'
            ]
        ],
        'protection_policy' => GuardInterface::POLICY_ALLOW
    ]
];
