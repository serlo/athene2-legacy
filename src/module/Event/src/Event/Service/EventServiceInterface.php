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
namespace Event\Service;

use User\Service\UserServiceInterface;
use Language\Service\LanguageServiceInterface;
use Event\Entity\EventLogInterface;


interface EventServiceInterface
{
    /**
     * 
     * @return EventLogInterface
     */
    public function getEntity();

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
     * @return \Datetime
     */
    public function getTimestamp();

    /**
     * Gets the actor.
     *
     * @return UserServiceInterface
     */
    public function getActor();

    /**
     * Gets the language.
     *
     * @return LanguageServiceInterface
     */
    public function getLanguage();

    /**
     * Gets the event name.
     *
     * @return string
     */
    public function getName();

    /**
     *
     * @return UuidHolder
     */
    public function getParameter($name);
    
    /**
     * 
     * @param EventLogInterface $entity
     * @return $this
     */
    public function setEntity(EventLogInterface $entity);
}