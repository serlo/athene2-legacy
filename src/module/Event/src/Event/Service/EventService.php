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

use Event\Entity\EventLogInterface;

class EventService implements EventServiceInterface
{
    use\User\Manager\UserManagerAwareTrait,\Language\Manager\LanguageManagerAwareTrait,\Uuid\Manager\UuidManagerAwareTrait;

    /**
     *
     * @var EventLogInterface
     */
    protected $entity;

    public function getActor()
    {
        $user = $this->getEntity()->getActor();
        return $this->getUserManager()->getUser($user->getId());
    }

    public function getName()
    {
        return $this->getEntity()
            ->getEvent()
            ->getName();
    }

    public function getId()
    {
        return $this->getEntity()->getId();
    }

    public function getLanguage()
    {
        $language = $this->getEntity()->getLanguage();
        return $this->getLanguageManager()->getLanguage($language->getId());
    }

    public function getObject()
    {
        $object = $this->getEntity()->getObject();
        return $this->getUuidManager()->getService($object->getId());
    }

    public function getParameter($name)
    {
        $object = $this->getEntity()->getParameter($name);
        return $this->getUuidManager()->getService($object->getId());
    }

    public function getTimestamp()
    {
        return $this->getEntity()->getTimestamp();
    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function setEntity(EventLogInterface $entity)
    {
        $this->entity = $entity;
        return $this;
    }
}