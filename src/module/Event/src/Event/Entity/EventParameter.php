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

use Doctrine\ORM\Mapping as ORM;
use Uuid\Entity\UuidInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="event_parameter")
 */
class EventParameter implements EventParameterInterface
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="EventLog", inversedBy="parameters")
     */
    protected $log;

    /**
     * @ORM\ManyToOne(targetEntity="EventParameterName")
     */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="Uuid\Entity\Uuid")
     */
    protected $uuid;

    public function getId()
    {
        return $this->id;
    }

    public function getLog()
    {
        return $this->log;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getObject()
    {
        return $this->uuid;
    }

    public function setLog(EventLogInterface $log)
    {
        $this->log = $log;
        return $this;
    }

    public function setName(EventParameterNameInterface $name)
    {
        $this->name = $name;
        return $this;
    }

    public function setObject(UuidInterface $uuid)
    {
        $this->uuid = $uuid;
        return $this;
    }
}