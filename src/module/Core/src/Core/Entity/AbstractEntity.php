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

    public function toArray(){
    	return $this->getArrayCopy();
    }

    /**
     * Gets a value. If a method `get$property` exists, it will be called:
     *
     * 		$entity->get('test');
     * 		$entity->getTest(); // equal to the above
     *
     * @see \Core\Entity\ModelInterface::get()
     */
    public function get($property){
    	$method = 'get'.$property;
    	if(method_exists($this, $method)){
    		return $this->$method();
    	}
        return $this->$property;
    }
    
    /**
     * Sets a value. If a method `set$property` exists, it will be called:
     * 
     * 		$entity->set('test','bar'); 
     * 		$entity->setTest('bar'); // equal to the above
     * 
     * @see \Core\Entity\ModelInterface::set()
     */
    public function set($property, $value){
    	$method = 'set'.$property;
    	if(method_exists($this, $method)){
    		$this->$method($value);
    	} else {
        	$this->$property = $value;
    	}
        return $this;
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
    	// TODO check for NOT NULL tables and force to populate them (or throw UnstatisfiedException)
    	
        foreach($data as $field => $value)
        	$this->set($field, $value);
        
        return $this;
    }
}