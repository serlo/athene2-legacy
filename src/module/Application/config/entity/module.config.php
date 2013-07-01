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
namespace Entity;

return array (
		'di' => array (
				'allowed_controllers' => array (
						'Application\Entity\LearningObject\Exercise\Controller\TextExerciseController',
						'Application\Entity\Provider\Repository\Controller\RepositoryController' 
				),
				'definition' => array (
						'class' => array (
								'Application\Entity\LearningObject\Exercise\Controller\TextExerciseController' => array (
										'setEntityManager' => array (
												'required' => 'true' 
										) 
								),
								'Application\Entity\Provider\Repository\Controller\RepositoryController' => array (
										'setEntityManager' => array (
												'required' => 'true' 
										) 
								) 
						) 
				),
				'instance' => array (
						'preferences' => array (
								'Entity\EntityManagerInterface' => 'Entity\EntityManager' 
						) 
				) 
		),
		'router' => array (
				'routes' => array (
						'entity' => array (
								'type' => 'Zend\Mvc\Router\Http\Segment',
								'options' => array (
										'route' => '/entity',
										'defaults' => array (
										) 
								),
								'child_routes' => array (
										'provider' => array (
												'type' => 'Zend\Mvc\Router\Http\Segment',
												'options' => array (
														'route' => '',
														'defaults' => array () 
												),
												'child_routes' => array (
														'repository' => array (
																'type' => 'Zend\Mvc\Router\Http\Segment',
																'options' => array (
																		'route' => '/repository/:action/:entity[/:revision]',
																		'defaults' => array (
																				'controller' => 'Application\Entity\Provider\Repository\Controller\RepositoryController',
																				'provider' => 'repository',
																		) 
																) 
														) 
												) 
										),
										'core' => array (
												'type' => 'Zend\Mvc\Router\Http\Segment',
												'options' => array (
														'route' => '/:action[/:id]',
														'defaults' => array (
																'controller' => 'Application\Entity\Controller\EntityController',
														) 
												) 
										) 
								) 
						) 
				) 
		),
		'zfcrbac' => array (
				'firewalls' => array (
						'ZfcRbac\Firewall\Controller' => array (
								array (
										'controller' => 'Application\LearningObject\Exercise\Controller\TextExerciseController',
										'actions' => 'update',
										'roles' => 'login' 
								),
								array (
										'controller' => 'Application\LearningObject\Exercise\Controller\TextExerciseController',
										'actions' => 'show',
										'roles' => 'guest' 
								),
								array (
										'controller' => 'Application\LearningObject\Exercise\Controller\TextExerciseController',
										'actions' => array (
												'history',
												'checkout',
												'trash-revision',
												'show-revision' 
										),
										'roles' => 'helper' 
								),
								array (
										'controller' => 'Application\LearningObject\Exercise\Controller\TextExerciseController',
										'actions' => array (
												'purge-revision',
												'create' 
										),
										'roles' => 'admin' 
								) 
						) 
				) 
		) 
);


