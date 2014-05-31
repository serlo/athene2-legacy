<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Event;

use Doctrine\Common\Collections\Collection;
use Event\Entity\EventInterface;
use Event\Entity\EventLogInterface;
use Instance\Entity\InstanceInterface;
use Uuid\Entity\UuidInterface;
use Zend\Paginator\Paginator;

interface EventManagerInterface
{
    /**
     * @param int $userId
     * @return EventLogInterface[]|Collection
     */
    public function findEventsByActor($userId);

    /**
     * @param int   $objectId
     * @param bool  $recursive
     * @param array $filter
     * @return EventLogInterface[]|Collection
     */
    public function findEventsByObject($objectId, $recursive = true, array $filter = []);

    /**
     * Finds an event by it's name
     *
     * @param string $eventName
     * @return EventInterface
     */
    public function findTypeByName($eventName);

    /**
     * @param int $id
     * @return EventLogInterface
     */
    public function getEvent($id);

    /**
     * @param int $page
     * @param int $limit
     * @return Paginator
     */
    public function findAll($page, $limit = 100);

    /**
     * @param string            $eventName
     * @param InstanceInterface $instance
     * @param UuidInterface     $uuid
     * @param array             $parameters
     * @return EventLogInterface
     */
    public function logEvent(
        $eventName,
        InstanceInterface $instance,
        UuidInterface $uuid,
        array $parameters = []
    );
}
