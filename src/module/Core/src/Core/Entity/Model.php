<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

abstract class Model
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * Magic getter to expose protected properties.
     *
     * @param string $property            
     * @return mixed
     *
     */
    public function __get ($property)
    {
        return $this->$property;
    }

    /**
     * Magic setter to save protected properties.
     *
     * @param string $property            
     * @param mixed $value            
     *
     */
    public function __set ($property, $value)
    {
        $this->$property = $value;
    }

    /**
     * Convert the object to an array.
     *
     * @return array
     */
    public function getArrayCopy ()
    {
        return get_object_vars($this);
    }

    /**
     * does the associated element exist?
     * 
     * @return boolean
     */
    public function exists ($association)
    {
        return $this->$association !== NULL;
    }
}