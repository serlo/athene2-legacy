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
namespace Event;

use Uuid\Entity\UuidHolder;
use User\Entity\UserInterface;
use Language\Entity\LanguageInterface;
use Zend\EventManager\SharedEventManager;

class EventManager implements EventManagerInterface
{
    use \ClassResolver\ClassResolverAwareTrait,\Common\Traits\ObjectManagerAwareTrait,\Common\Traits\ConfigAwareTrait;

    /**
     *
     * @var SharedEventManager
     */
    protected $sharedEventManager;

    /**
     *
     * @return SharedEventManager $sharedEventManager
     */
    public function getSharedEventManager()
    {
        return $this->sharedEventManager;
    }

    /**
     *
     * @param SharedEventManager $sharedEventManager            
     * @return $this
     */
    public function setSharedEventManager(SharedEventManager $sharedEventManager)
    {
        $this->sharedEventManager = $sharedEventManager;
        return $this;
    }

    protected function getDefaultConfig()
    {
        return array(
            'listener' => array()
        );
    }

    public function attachListeners()
    {
        foreach ($this->getOption('listeners') as $listener) {
            $this->getSharedEventManager()->attachAggregate($this->getServiceLocator()
                ->get($listener));
            // $this->getEventManager()->attachAggregate($this->getServiceLocator()
            // ->get($listener));
        }
    }

    public function logEvent($uri, LanguageInterface $language, UserInterface $actor, UuidHolder $uuid)
    {
        $className = $this->getClassResolver()->resolveClassName('Event\Entity\EventLogInterface');
        
        /* @var $log Entity\EventLogInterface */
        $log = new $className();
        
        $log->setEvent($this->findEventByRoute($uri));
        
        $log->setUuid($uuid->getUuidEntity());
        $log->setActor($actor);
        $log->setLanguage($language);
        
        $this->getObjectManager()->persist($log);
        return $this;
    }

    private function findEventByRoute($route)
    {
        $className = $this->getClassResolver()->resolveClassName('Event\Entity\EventInterface');
        $event = $this->getObjectManager()
            ->getRepository($className)
            ->findOneBy(array(
            'route' => $route
        ));
        if (! is_object($event)) {
            $event = new $className();
            $event->setRoute($route);
            $this->getObjectManager()->persist($event);
        }
        
        return $event;
    }

    private function findVerb($verb)
    {
        $className = $this->getClassResolver()->resolveClassName('Event\Entity\EventStringInterface');
        $string = $this->getObjectManager()
            ->getRepository($className)
            ->findOneBy(array(
            'string' => $verb
        ));
        if (! is_object($string)) {
            $string = new $className();
            $string->setString($verb);
            $this->getObjectManager()->persist($string);
        }
        
        return $string;
    }
}