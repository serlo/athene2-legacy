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
namespace Contexter;

use Zend\ServiceManager\ServiceLocatorInterface;
use Contexter\Service\ContextService;
return array(
    'Manager\ContextManager' => array(
        'router' => array(
            'adapters' => array(
                array(
                    'adapter' => __NAMESPACE__ . '\Adapter\EntityPluginControllerAdapter',
                    'controllers' => array(
                        [
                            'controller' => 'Entity\Plugin\Repository\Controller\RepositoryController',
                            'action' => 'addRevision'
                        ]
                    )
                )
            )
        )
    ),
    'view_helpers' => array(
        'factories' => array(
            'contexter' => function ($helperPluginManager)
            {
                $plugin = new \Contexter\View\Helper\Contexter();
                $plugin->setRouter($helperPluginManager->getServiceLocator()
                    ->get('Contexter\Router\Router'));
                return $plugin;
            }
        )
    ),
    'class_resolver' => array(
        'Contexter\Entity\ContextInterface' => 'Contexter\Entity\Service\ContextService',
        'Contexter\Service\ContextServiceInterface' => 'Contexter\Service\ContextService',
        'Contexter\Entity\TypeInterface' => 'Contexter\Entity\Type',
        'Contexter\Entity\RouteInterface' => 'Contexter\Entity\Route',
        'Contexter\Entity\RouteParameterInterface' => 'Contexter\Entity\RouteParameter'
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
);

