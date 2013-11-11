<?php 
return array(
    'zfcrbac' => array(
        'firewalls' => array(
            'ZfcRbac\Firewall\Controller' => array(
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
            ),
            'ZfcRbac\Firewall\Route' => array(
                array(
                    'route' => 'entity/plugin/link/order',
                    'roles' => 'moderator'
                ),
                array(
                    'route' => 'entity/create',
                    'roles' => 'moderator'
                ),
            )
        )
    )
);