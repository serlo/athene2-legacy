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

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="event")
 */
class Event implements EventInterface
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $route;

    /**
     * @ORM\Column(type="string")
     */
    protected $description;

    /**
     *
     * @return field_type $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @return field_type $uri
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     *
     * @return field_type $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     *
     * @param field_type $id            
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     *
     * @param field_type $uri            
     * @return $this
     */
    public function setRoute($uri)
    {
        $this->route = $uri;
        return $this;
    }

    /**
     *
     * @param field_type $description            
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
}