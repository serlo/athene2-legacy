<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Event;

use Event\Entity\EventInterface;
use Event\Entity\EventLogInterface;
use Instance\Entity\InstanceInterface;
use User\Entity\UserInterface;
use Uuid\Entity\UuidInterface;

interface EventManagerInterface
{
    /**
     * Logs an event and tells the UnitOfWork to store it in the database.
     * Caution: You need to manually handle flushing.
     * Example:
     * <code>
     * $eventManager->logEvent('eventA', $LanguageA, $userEntityA, $objectEntityA);
     * $eventManager->logEvent('eventB', $LanguageB, $userEntityB, $objectEntityB);
     * $eventManager->getObjectManager()->flush(); // Making the changes above persistent
     * </code>


*
*@param string            $eventName
     * @param InstanceInterface $instance
     * @param UserInterface     $actor
     * @param UuidInterface     $uuid
     * @param array             $parameters
     * @return EventLogInterface
     */
    public function logEvent(
        $eventName,
        InstanceInterface $instance,
        UserInterface $actor,
        UuidInterface $uuid,
        array $parameters = array()
    );

    /**
     * Finds an event by it's name
     *
     * @param string $eventName
     * @return EventInterface
     */
    public function findTypeByName($eventName);

    /**
     * @param int   $objectId
     * @param bool  $recursive
     * @param array $filter
     * @return EventLogInterface[]
     */
    public function findEventsByObject($objectId, $recursive = true, array $filter = array());

    /**
     * @param int $userId
     * @return EventLogInterface[]
     */
    public function findEventsByActor($userId);

    /**
     * @param int $id
     * @return EventLogInterface
     */
    public function getEvent($id);
}