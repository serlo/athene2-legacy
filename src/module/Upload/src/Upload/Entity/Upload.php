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
namespace Upload\Entity;

use Uuid\Entity\UuidEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="upload")
 */
class Upload extends UuidEntity implements UploadInterface
{

    /**
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="Uuid\Entity\Uuid", inversedBy="upload")
     * @ORM\JoinColumn(name="id", referencedColumnName="id")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", unique=true, length=60)
     */
    protected $location;

    /**
     * @ORM\Column(type="integer")
     */
    protected $size;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=20)
     */
    protected $type;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $timestamp;
    
    public function getLocation()
    {
        return $this->location;
    }
    
    public function getSize()
    {
        return $this->size;
    }
    
    public function getFilename()
    {
        return $this->name;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getDateTime()
    {
        return $this->timestamp;
    }

    public function setLocation($location)
    {
        $this->location = $location;
        return $this;
    }

    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }

    public function setFilename($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }
}