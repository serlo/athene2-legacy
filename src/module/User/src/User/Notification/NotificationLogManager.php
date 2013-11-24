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
namespace User\Notification;

use Uuid\Entity\UuidInterface;

class NotificationLogManager implements NotificationLogManagerInterface
{
    use\Common\Traits\ObjectManagerAwareTrait,\Event\EventManagerAwareTrait, \ClassResolver\ClassResolverAwareTrait;
    
    /*
     * (non-PHPdoc) @see \User\Notification\NotificationLogManagerInterface::logEvent()
     */
    public function logEvent($route, \User\Entity\UserInterface $actor, UuidInterface $object, UuidInterface $reference = NULL)
    {
        $className = $this->getClassResolver()->resolveClassName('User\Notification\Entity\NotificationLogInterface');
        
        /* @var $log \User\Notification\Entity\NotificationLogInterface */
        $log = new $className();
        
        $log->setEvent($this->getEventManager()
            ->findTypeByName($route));
        
        $log->setObject($object);
        if ($reference !== NULL) {
            $log->setReference($reference);
        }
        $log->setActor($actor);
        
        $this->getObjectManager()->persist($log);
        return $this;
    }
}