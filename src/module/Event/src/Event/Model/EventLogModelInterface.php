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
namespace Event\Model;

use User\Entity\UserInterface;
use Uuid\Entity\UuidInterface;
use Uuid\Entity\UuidHolder;
use Datetime;
use Event\Entity\EventInterface;
use Language\Model\LanguageModelAwareInterface;
use Common\Model\Wrapable;
use Event\Entity\EventParameterInterface;

interface EventLogModelInterface extends LanguageModelAwareInterface, Wrapable
{

    /**
     * Returns the id.
     *
     * @return int
     */
    public function getId();

    /**
     * Gets the associated object (uuid).
     *
     * @return UuidHolder
     */
    public function getObject();
    
    /**
     * 
     * @return Datetime
     */
    public function getTimestamp();

    /**
     * Gets the actor.
     *
     * @return UserInterface
     */
    public function getActor();

    /**
     * Gets the event.
     *
     * @return EventInterface
     */
    public function getEvent();
    
    /**
     * 
     * @return self
     */
    public function getEntity();

    /**
     *
     * @return EventParameterInterface[]
     */
    public function getParameters();

    /**
     *
     * @return UuidHolder
     */
    public function getParameter($name);
    
    /**
     *
     * @param EventParameterInterface $parameter            
     * @return $this
     */
    public function addParameter(EventParameterInterface $parameter);

    /**
     * Sets the associated object (uuid)
     *
     * @param UuidInterface $uuid            
     * @return $this
     */
    public function setObject(UuidInterface $uuid);

    /**
     * Sets the event.
     *
     * @param EventInterface $event            
     * @return $this
     */
    public function setEvent(EventInterface $event);

    /**
     * Sets the actor.
     *
     * @param UserInterface $actor            
     * @return $this
     */
    public function setActor(UserInterface $actor);
}