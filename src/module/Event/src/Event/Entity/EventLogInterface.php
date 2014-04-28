<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Event\Entity;

use Datetime;
use Instance\Entity\InstanceAwareInterface;
use User\Entity\UserInterface;
use Uuid\Entity\UuidInterface;

interface EventLogInterface extends InstanceAwareInterface

{

    /**
     * Returns the id
     *
     * @return int
     */
    public function getId();

    /**
     * Gets the associated object (uuid)
     *
     * @return UuidInterface
     */
    public function getObject();

    /**
     * @return Datetime
     */
    public function getTimestamp();

    /**
     * Gets the actor
     *
     * @return UserInterface
     */
    public function getActor();

    /**
     * Gets the event
     *
     * @return EventInterface
     */
    public function getEvent();

    /**
     * Returns the name
     *
     * @return string
     */
    public function getName();

    /**
     * @return EventParameterInterface[]
     */
    public function getParameters();

    /**
     * @return UuidInterface
     */
    public function getParameter($name);

    /**
     * @param EventParameterInterface $parameter
     * @return self
     */
    public function addParameter(EventParameterInterface $parameter);

    /**
     * Sets the associated object (uuid)
     *
     * @param UuidInterface $uuid
     * @return self
     */
    public function setObject(UuidInterface $uuid);

    /**
     * Sets the event.
     *
     * @param EventInterface $event
     * @return self
     */
    public function setEvent(EventInterface $event);

    /**
     * Sets the actor.
     *
     * @param UserInterface $actor
     * @return self
     */
    public function setActor(UserInterface $actor);
}