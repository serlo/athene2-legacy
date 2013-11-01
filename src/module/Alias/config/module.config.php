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
namespace Alias;

use Zend\ServiceManager\ServiceLocatorInterface;
return array(
    'alias_manager' => array(
        'aliases' => array(
            'blogPost' => array(
                'tokenize' => 'blog/{category}/{title}',
                'provider' => 'Blog\Provider\TokenizerProvider',
                'fallback' => 'blog/{category}/{id}-{title}'
            ),
            'page' => array(
                'tokenize' => 'page/{slug}',
                'provider' => 'Page\Provider\TokenizerProvider',
                'fallback' => 'page/{id}-{slug}'
            )
        )
    ),
    'class_resolver' => array(
        __NAMESPACE__ . '\Entity\AliasInterface' => __NAMESPACE__ . '\Entity\Alias'
    ),
    'router' => array(
        'routes' => array(
            'alias' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/alias/:alias',
                    'defaults' => array(
                        'controller' => 'Alias\Controller\AliasController',
                        'action' => 'forward'
                    ),
                    'constraints' => array(
                        'alias' => '(.)+'
                    )
                ),
                'may_terminate' => true
            )
        )
    ),
    'service_manager' => array(
        'factories' => array(
            'Alias\AliasManager' => function (ServiceLocatorInterface $sm)
            {
                $config = $sm->get('config')['alias_manager'];
                
                $service = new AliasManager();
                $service->setConfig($config);
                $service->setClassResolver($sm->get('ClassResolver\ClassResolver'));
                $service->setObjectManager($sm->get('EntityManager'));
                $service->setTokenizer($sm->get('Token\Tokenizer'));
                
                return $service;
            }
        )
    ),
    'di' => array(
        'allowed_controllers' => array(
            'Alias\Controller\AliasController'
        ),
        'definition' => array(
            'class' => array(
                __NAMESPACE__ . '\Controller\AliasController' => array(
                    'setAliasManager' => array(
                        'required' => 'true'
                    ),
                    'setLanguageManager' => array(
                        'required' => 'true'
                    )
                ),
                /*'Alias\AliasManager' => array(
                    'setObjectManager' => array(
                        'required' => 'true'
                    ),
                    'setClassResolver' => array(
                        'required' => 'true'
                    )
                )*/
                __NAMESPACE__ . '\Listener\BlogControllerListener' => array(
                    'setAliasManager' => array(
                        'required' => 'true'
                    )
                ),
                __NAMESPACE__ . '\Listener\PageControllerListener' => array(
                    'setAliasManager' => array(
                        'required' => 'true'
                    )
                )
            )
        ),
        'instance' => array(
            'preferences' => array(
                __NAMESPACE__ . '\AliasManagerInterface' => __NAMESPACE__ . '\AliasManager'
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
    ),
    'view_helpers' => array(
        'factories' => array(
            'url' => function ($helperPluginManager)
            {
                $serviceLocator = $helperPluginManager->getServiceLocator();
                $view_helper = new \Alias\View\Helper\Url();
                
                $router = \Zend\Console\Console::isConsole() ? 'HttpRouter' : 'Router';
                $view_helper->setRouter($serviceLocator->get($router));
                
                $view_helper->setAliasManager($serviceLocator->get('Alias\AliasManager'));
                $view_helper->setLanguageManager($serviceLocator->get('Language\Manager\LanguageManager'));
                
                $match = $serviceLocator->get('application')
                    ->getMvcEvent()
                    ->getRouteMatch();
                
                if ($match instanceof RouteMatch) {
                    $view_helper->setRouteMatch($match);
                }
                
                return $view_helper;
            }
        )
    )
);