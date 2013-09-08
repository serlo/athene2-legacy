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

use Doctrine\ORM\Mapping as ORM;

/**
 * An issue
 *
 * @ORM\Entity
 * @ORM\Table(name="uuid")
 */
class Uuid implements UuidInterface
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=30, unique=true)
     */
    protected $uuid;

    function __construct ()
    {
        $this->uuid = hash('crc32b', uniqid('uuid.',true));
    }

    /**
     *
     * @return field_type $id
     */
    public function getId ()
    {
        return $this->id;
    }

    /**
     *
     * @return string $uuid
     */
    public function getUuid ()
    {
        return $this->uuid;
    }

    /**
     *
     * @param field_type $id            
     * @return $this
     */
    public function setId ($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     *
     * @param string $uuid            
     * @return $this
     */
    public function setUuid ($uuid)
    {
        $this->uuid = $uuid;
        return $this;
    }
    
    public function hydrate(UuidHolder $entity){
        $entity->setUuid($this);
        return $this;
    }
}