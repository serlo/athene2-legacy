<?php
namespace Subject;

use Zend\Mvc\MvcEvent;
use Zend\EventManager\Event;
// use Zend\Mvc\ModuleRouteListener;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    

    public function onBootstrap(MvcEvent $e)
    {
        $app      = $e->getTarget();
        $serviceManager       = $app->getServiceManager();
    
        // Load Subjects
        //$listener = $serviceManager->get('Subject\Hydrator\Route');
        //$listener->setPath(__DIR__ . '/config/subject/');
        //$app->getEventManager()->attach('route', array($listener, 'onPreRoute'), 5);
    
        // Route translator
        //$app->getEventManager()->attach('route', array($this, 'onPreRoute'), 4);
        
        // Load Subjects
        /*$listener = $serviceManager->get('Subject\Hydrator\Route');
        $listener->setPath(__DIR__ . '/config/subject/');
        $app->getEventManager()->attach('route', array($listener, 'onPreRoute'), 5);*/
        
        $hydrator = $serviceManager->get('Subject\Hydrator\Navigation');
        $hydrator->setPath(__DIR__ . '/config/navigation/');
        
        $this->addEntityManagerListener($serviceManager, $e);
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
                    
                    //$url = $mvce->getRouter()->assemble(array('entity' => $entity->getId(), 'action' => 'add-revision' ), array('name' => 'entity/plugin/repository'));
                }
            }
        }, 2);
    }
    

    public function getAutoloaderConfig()
    {
        $namespaces = array(
        );
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }
    
    /*public function onBootstrap($e)
    {
        $app      = $e->getTarget();
        $serviceManager       = $app->getServiceManager();
        $listener = $serviceManager->get('Subject\Hydrator\Route');
        $app->getEventManager()->attach('route', array($listener, 'onPreRoute'), 5);
    }*/
}