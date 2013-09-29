<?php
namespace Page;

return array(
		'view_manager' => array(
				'template_path_stack' => array(
						__DIR__ . '/../view'
				)
		),
		'router' => array(
				'routes' => array(
						'page' => array(
								'type' => 'Zend\Mvc\Router\Http\Literal',
								'options' => array(
										'route' => '/page',
										'defaults' => array(
												'controller' => 'Page\Controller\Index',
												'action' => 'index'
										)
								)),
						'edit' => array(
								'type' => 'Zend\Mvc\Router\Http\Segment',
								'options' => array(
										'route' => '/page/:slug/edit',
										'defaults' => array(
												'controller' => 'Page\Controller\Index',
												'action' => 'edit',
						
										)
								)
						),
						
						'article' => array(
								'type' => 'Zend\Mvc\Router\Http\Segment',
								'options' => array(
										'route' => '/page/:slug/',
										'defaults' => array(
												'controller' => 'Page\Controller\Index',
												'action' => 'article',

										)
								)
						)
						
						
				)
		),
		'controllers' => array(
				'factories' => array(
						'Page\Controller\Index' => 'Page\Controller\IndexControllerFactory'
				)
		)
		,
		'service_manager' => array(
				'invokables' => array(

				),
				'factories' => array(
						'Page\Service\PageService' => function  ($sm)
						{
							$srv = new \Page\Service\PageService();
							$srv->setEntityManager($sm->get('EntityManager'));
							return $srv;
						}
				)
		),
		'doctrine' => array(
				'driver' => array(
						__NAMESPACE__ . '_driver' => array(
								'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
								'cache' => 'array',
								'paths' => array(
										__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity'
								)
						),
						'orm_default' => array(
								'drivers' => array(
										__NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
								)
						)
				)
		)
);