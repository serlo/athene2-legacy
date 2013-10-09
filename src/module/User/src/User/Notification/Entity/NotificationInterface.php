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
namespace User\Notification\Entity;

use \User\Entity;
use Doctrine\ORM\PersistentCollection;
use Doctrine\Common\Collections\ArrayCollection;

interface NotificationInterface
{

    /**
     *
     * @param bool $seen            
     * @return $this
     */
    public function setSeen($seen);

    /**
     *
     * @return bool
     */
    public function getSeen();

    /**
     *
     * @return Entity\User
     */
    public function getUser();

    /**
     *
     * @param Entity\User $user            
     * @return $this
     */
    public function setUser(Entity\UserInterface $user);

    /**
     *
     * @return PersistentCollection
     */
    public function getEvents();

    /**
     *
     * @param NotificationEventInterface $event            
     * @return $this
     */
    public function addEvent(NotificationEventInterface $event);

    /**
     *
     * @return ArrayCollection
     */
    public function getActors();

    /**
     *
     * @return ArrayCollection
     */
    public function getObjects();

    /**
     *
     * @return ArrayCollection
     */
    public function getReferences();
}