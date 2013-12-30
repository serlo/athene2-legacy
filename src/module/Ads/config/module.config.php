<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author  Jakob Pfab (jakob.pfab@serlo.org)
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Ads;

return array(
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view'
        )
    ),
    'router' => array(
        'routes' => array(
            'ads' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'may_terminate' => true,
                'options' => array(
                    'route' => '/ads',
                    'defaults' => array(
                        'controller' => 'Ads\Controller\AdsController',
                        'action' => 'index'
                    )
                ),
                'child_routes' => array(
                    'createad' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'may_terminate' => true,
                        'options' => array(
                            'route' => '/add',
                            'defaults' => array(
                                'controller' => 'Ads\Controller\AdsController',
                                'action' => 'add'
                            )
                        )
                    )
                )
            )
        )
    ),
    
    'class_resolver' => array(
        'Ads\Entity\AdInterface' => 'Ads\Entity\Ad',
        
    ),
    'zfc_rbac' => array(
        
        'guards' => array(
            'ZfcRbac\Guard\ControllerGuard' => array(
                array(
                    'controller' => 'Ads\Controller\AdsController',
                    'actions' => array(
                        'createRepository',
                        'index'
                    ),
                    'roles' => 'moderator'
                ),
                array(
                    'controller' => 'Ads\Controller\AdsController',
                    'actions' => array(
                        'article'
                    ),
                    'roles' => 'guest'
                )
            )
        )
    ),
    'di' => array(
        'allowed_controllers' => array(
            __NAMESPACE__ . '\Controller\AdsController'
        ),
        'definition' => array(
            'class' => array(
                
                'Ads\Controller\AdsController' => array(
                    'setObjectManager' => array(
                        'required' => 'true'
                    ),
                    'setLanguageManager' => array(
                        'required' => 'true'
                    ),
                    'setUserManager' => array(
                        'required' => 'true'
                    ),
                    'setAdsManager' => array(
                        'required' => true
                    ),
                    'setUploadManager' => array(
                        'required' => true
                    )
                ),
                'Ads\Manager\AdsManager' => array(
                    
                    'setLanguageManager' => array(
                        'required' => 'true'
                    ),
                    'setClassResolver' => array(
                        'required' => 'true'
                    ),
                    'setUserManager' => array(
                        'required' => true
                    ),
                    'setObjectManager' => array(
                        'required' => true
                    ),
                    'setUploadManager' => array(
                        'required' => true
                    )
                )
            )
        ),
        'instance' => array(
            'preferences' => array(
                __NAMESPACE__ . '\Manager\AdsManagerInterface' => __NAMESPACE__ . '\Manager\AdsManager'
            )
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


