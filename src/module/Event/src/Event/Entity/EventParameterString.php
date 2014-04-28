<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Event\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="event_parameter_string")
 */
class EventParameterString
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="EventParameter", inversedBy="object")
     * @ORM\JoinColumn(name="event_parameter_id", referencedColumnName="id")
     */
    protected $eventParameter;

    /**
     * @ORM\Column(type="string")
     */
    protected $value;

    public function __construct(EventParameter $eventParameter, $value)
    {
        $this->value          = $value ? $value : '';
        $this->eventParameter = $eventParameter;
    }

    /**
     * @return EventParameter
     */
    public function getEventParameter()
    {
        return $this->eventParameter;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getValue()
    {
        return $this->value;
    }
}