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
namespace Uuid\Entity;

class UuidEntity implements UuidHolder
{

    public function __construct($uuid = NULL)
    {
        if ($uuid) {
            $this->id = $uuid;
        }
    }

    public function getUuid()
    {
        return $this->getUuidEntity()->getUuid();
    }

    public function getId()
    {
        return $this->getUuidEntity()->getId();
    }

    public function setUuid(UuidInterface $uuid = null)
    {
        $this->id = $uuid;
        return $this;
    }

    public function getUuidEntity()
    {
        return $this->id;
    }
    
    public function getTrashed()
    {
        return $this->getUuidEntity()->getTrashed();
    }
    
    public function setTrashed($trashed)
    {
        $this->getUuidEntity()->setTrashed($trashed);
        return $this;
    }
}