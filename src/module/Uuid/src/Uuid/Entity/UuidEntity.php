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
use Core\Exception\UnknownPropertyException;

abstract class UuidEntity implements UuidHolder
{
    /**
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="Uuid\Entity\Uuid")
     * @ORM\JoinColumn(name="id", referencedColumnName="id")
     */
    protected $id;
    
    public function __construct($uuid = NULL){
        if($uuid){
            $this->id = $uuid;
        }
    }
    
    /**
     * @return string $uuid
     */
    public function getUuid ()
    {
        return $this->id->getUuid();
    }

	/**
     * @param field_type $id
     * @return $this
     */
    public function setId ($id)
    {
        return $this->setUuid($id);
    }
    
    /**
     * 
     */
    public function getId ()
    {
        return $this->id->getId();
    }

	/**
     * @param string $uuid
     * @return $this
     */
    public function setUuid (Uuid $uuid = null)
    {
        $this->id = $uuid;
        return $this;
    }

    public function toArray ()
    {
        return $this->getArrayCopy();
    }

    /**
     * Gets a value.
     * If a method `get$property` exists, it will be called:
     *
     * $entity->get('test');
     * $entity->getTest(); // equal to the above
     *
     * @see \Core\Entity\ModelInterface::get()
     */
    public function get ($property)
    {
        $method = 'get' . $property;
        if (method_exists($this, $method)) {
            return $this->$method();
        }
        if (! property_exists($this, $property))
            throw new UnknownPropertyException('Property `' . $property . '` not found.');
        
        return $this->$property;
    }

    /**
     * Sets a value.
     * If a method `set$property` exists, it will be called:
     *
     * $entity->set('test','bar');
     * $entity->setTest('bar'); // equal to the above
     *
     * @see \Core\Entity\ModelInterface::set()
     */
    public function set ($property, $value)
    {
        $method = 'set' . $property;
        if (method_exists($this, $method)) {
            $this->$method($value);
        } else {
            if (! property_exists($this, $property))
                throw new UnknownPropertyException('Property `' . $property . '` not found.');
            $this->$property = $value;
        }
        return $this;
    }

    /*public function populate (array $data)
    {
        // TODO check for NOT NULL tables and force to populate them (or throw UnstatisfiedException)
        //foreach ($data as $field => $value)
        //    $this->set($field, $value);
        
        return $this;
    }*/
}