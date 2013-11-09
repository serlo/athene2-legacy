<?php

return array(
    'zfcrbac' => array(
        'firewalls' => array(
            'ZfcRbac\Firewall\Controller' => array(
                array(
                    'controller' => 'Discussion\Controller\DiscussionController',
                    'actions' => array(
                        'start',
                        'comment',
                        'vote'
                    ),
                    'roles' => 'login'
                ),
                array(
                    'controller' => 'Discussion\Controller\DiscussionController',
                    'actions' => array(
                        'archive',
                        'trash'
                    ),
                    'roles' => 'moderator'
                ),
                array(
                    'controller' => 'Entity\Controller\EntityController',
                    'actions' => array(
                        'create'
                    ),
                    'roles' => 'login'
                ),
                array(
                    'controller' => 'Entity\Controller\EntityController',
                    'actions' => array(
                        'trash',
                        'restore'
                    ),
                    'roles' => 'moderator'
                ),
                array(
                    'controller' => 'Entity\Controller\EntityController',
                    'actions' => array(
                        'purge'
                    ),
                    'roles' => 'moderator'
                ),
                array(
                    'controller' => 'LearningResource\Plugin\Repository\Controller\RepositoryController',
                    'actions' => array(
                        'compare',
                        'history'
                    ),
                    'roles' => 'guest'
                ),
                array(
                    'controller' => 'LearningResource\Plugin\Repository\Controller\RepositoryController',
                    'actions' => 'add-revision',
                    'roles' => 'login'
                ),
                array(
                    'controller' => 'LearningResource\Plugin\Repository\Controller\RepositoryController',
                    'actions' => array(
                        'trash-revision',
                        'checkout'
                    ),
                    'roles' => 'helper'
                ),
                array(
                    'controller' => 'LearningResource\Plugin\Repository\Controller\RepositoryController',
                    'actions' => 'purge-revision',
                    'roles' => 'admin'
                ),
                array(
                    'controller' => 'LearningResource\Plugin\Taxonomy\Controller\TaxonomyController',
                    'actions' => 'update',
                    'roles' => 'moderator'
                ),
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
                array(
                    'route' => 'entity/plugin/link/order',
                    'roles' => 'moderator'
                ),
                array(
                    'route' => 'entity/create',
                    'roles' => 'moderator'
                ),
                array(
                    'route' => 'taxonomy/term/sort-associated',
                    'roles' => 'moderator'
                ),
                array(
                    'route' => 'taxonomy/term/order',
                    'roles' => 'moderator'
                ),
                array(
                    'route' => 'user/role/add',
                    'roles' => 'sysadmin'
                )
            )
        )
    )
);