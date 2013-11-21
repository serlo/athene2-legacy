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