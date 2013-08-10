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
namespace ResourceManager;

use Zend\Mvc\MvcEvent;
use Zend\EventManager\Event;

class Module
{

    public function getConfig ()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap (MvcEvent $e)
    {
        $sm = $e->getApplication()->getServiceManager();
        
        $this->addEntityManagerListener($sm, $e);
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

    public function addEntityManagerListener ($sm, MvcEvent $mvce)
    {
        /**
         * Adds an entity to a subject, if a term is given
         */
        $sm->get('Entity\Manager\EntityManager')
            ->getEventManager()
            ->attach('create', function  (Event $e) use( $sm, $mvce)
        {
            $entity = $e->getParam('entity');
            
            if (isset($_GET['term']) && isset($_GET['subject'])) {
                $subjectManager = $sm->get('Subject\Manager\SubjectManager');
                $subject = $subjectManager->get($_GET['subject']);
                
                if ($subject->isPluginWhitelisted('topic')) {
                    $subject->topic()
                        ->addEntity($entity, $_GET['term']);
                    
                    $url = $mvce->getRouter()->assemble(array('entity' => $entity->getId(), 'action' => 'add-revision' ), array('name' => 'entity/plugin/repository'));
                }
            }
        }, 2);
    }
}