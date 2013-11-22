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
namespace Event\Entity;

use User\Entity\UserInterface;
use Uuid\Entity\UuidInterface;
use Language\Entity\LanguageInterface;

interface EventLogInterface
{

    /**
     * Returns the id.
     *
     * @return int
     */
    public function getId();

    /**
     * Sets the associated object (uuid)
     *
     * @param UuidInterface $uuid            
     * @return $this
     */
    public function setUuid(UuidInterface $uuid);

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

    /**
     * Sets the language.
     *
     * @param LanguageInterface $language            
     * @return $this
     */
    public function setLanguage(LanguageInterface $language);

    /**
     * Gets the associated object (uuid).
     *
     * @return UuidInterface
     */
    public function getUuid();

    /**
     * Gets the actor.
     *
     * @return UserInterface
     */
    public function getActor();

    /**
     * Gets the language.
     *
     * @return LanguageInterface
     */
    public function getLanguage();

    /**
     * Gets the event.
     *
     * @return EventInterface
     */
    public function getEvent();

    /**
     *
     * @return EventParameterInterface[]
     */
    public function getParameters();

    /**
     *
     * @param EventParameterInterface $parameter            
     * @return $this
     */
    public function addParameter(EventParameterInterface $parameter);
}