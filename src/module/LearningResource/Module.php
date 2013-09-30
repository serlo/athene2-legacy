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
namespace LearningResource;

use Zend\Mvc\MvcEvent;
use Zend\EventManager\Event;
use LearningResource\Plugin\Link\LinkPlugin;

class Module
{

    public function getConfig ()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig ()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }

    public function onBootstrap (MvcEvent $mvce)
    {
        $sm = $mvce->getApplication()->getServiceManager();
        
        // Links
        // an
        // Entity
        // to
        // another
        // entity
        $sm->get('Entity\Manager\EntityManager')
            ->getEventManager()
            ->attach('create', function  (Event $e) use( $sm, $mvce)
        {
            if (isset($_GET['to_entity']) && isset($_GET['scope'])) {
                $entityManager = $sm->get('Entity\Manager\EntityManager');
                $scope = (string) $_GET['scope'];
                
                $entityManager = $sm->get('Entity\Manager\EntityManager');
                $toEntity = $entityManager->get($_GET['to_entity']);
                $entity = $e->getParam('entity');
                
                if ($entity->isPluginWhitelisted($scope)) {
                    // Warning:
                    // Plugins
                    // shouldn't
                    // be
                    // invokable
                    if (is_object($entity->$scope()) && ! $entity->$scope() instanceof LinkPlugin)
                        throw new \Exception(sprintf('Scope `%s` is not an implementation of LinkPlugin', $scope));
                    
                    $addAsParent = (isset($_GET['as']) && strtolower($_GET['as']) == 'parent');
                    
                    if ($addAsParent) {
                        $entity->$scope()
                            ->addChild($toEntity);
                    } else {
                        $entity->$scope()
                            ->addParent($toEntity);
                    }
                    $entityManager->getObjectManager()->flush();
                } else {
                    throw new \RuntimeException(sprintf('Scope %s is not whitelisted.', $scope));
                }
            }
        }, 2);
        
        $sm->get('Entity\Manager\EntityManager')
            ->getEventManager()
            ->attach('create', function  (Event $e) use( $sm, $mvce)
        {
            $entity = $e->getParam('entity');
            if ($entity->isPluginWhitelisted('repository')) {
                
                $url = $mvce->getRouter()
                    ->assemble(array(
                    'entity' => $entity->getId(),
                    'action' => 'add-revision'
                ), array(
                    'name' => 'entity/plugin/repository'
                ));
                $response = $mvce->getResponse();
                
                $response->setHeaders($response->getHeaders()
                    ->addHeaderLine('Location', $url . '?ref=' . ($mvce->getRequest()
                    ->getHeader('HTTP_REFERER', '/'))));
                
                $response->setStatusCode(302);
                $response->sendHeaders();
                exit();
            }
        }, - 1000);
    }
}