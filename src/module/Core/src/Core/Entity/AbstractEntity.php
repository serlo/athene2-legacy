<?php

namespace Core\Entity;

use Doctrine\ORM\Mapping as ORM;

abstract class AbstractEntity implements EntityInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;
    
    public function getId(){
        return $this->id;
    }
    
    public function get($property){
        return $this->$property;
    }
    
    public function set($property, $value){
        return $this->$property = $value;
    }
    
    public function getArrayCopy ()
    {
        return get_object_vars($this);
    }
    
    public function exists ($association)
    {
        return $this->$association !== NULL;
    }
    
    public function populate(array $data){
        return $this->setFieldValues($data);
    }
    
    public function getFieldValue($field){
        return $this->get($field);
    }
    
    public function setFieldValue($field, $value){
        $this->set($field, $value);
        return $this;
    }
}