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
     *
     * @return int
     */
    public function getId();

    /**
     *
     * @param UuidInterface $uuid            
     * @return $this
     */
    public function setUuid(UuidInterface $uuid);

    /**
     *
     * @param EventInterface $event            
     * @return $this
     */
    public function setEvent(EventInterface $event);

    /**
     *
     * @param UserInterface $actor            
     * @return $this
     */
    public function setActor(UserInterface $actor);

    /**
     *
     * @param LanguageInterface $language            
     * @return $this
     */
    public function setLanguage(LanguageInterface $language);

    /**
     *
     * @return UuidInterface
     */
    public function getUuid();

    /**
     *
     * @return UserInterface
     */
    public function getActor();

    /**
     *
     * @return LanguageInterface
     */
    public function getLanguage();

    /**
     *
     * @return EventInterface
     */
    public function getEvent();
}