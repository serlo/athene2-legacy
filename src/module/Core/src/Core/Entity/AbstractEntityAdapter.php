<?php
namespace Core\Entity;

abstract class AbstractEntityAdapter implements EntityAdapterInterface
{
	private $_entity;
	
    public function __construct(EntityInterface $entity = NULL){
    	if($entity){
        	$this->setEntity($entity);
    	}
    }
    
	public function setEntity(EntityInterface $entity){
        $this->_entity = $entity;
        return $this;
    }
    
    public function getEntity(){
        return $this->_entity;
    }
    
    public function get($field){
        return $this->getEntity()->get($field);
    }
    
    public function set($field, $value){
        $this->getEntity()->set($field, $value);
        return $this;
    }
    
    public function getId(){
        return $this->getEntity()->get('id');
    }
    
    public function delete(){
        return $this;
    }
}