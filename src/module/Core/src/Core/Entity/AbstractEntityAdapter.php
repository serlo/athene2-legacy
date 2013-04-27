<?php
namespace Core\Entity;

use Core\Structure\AbstractAdapter;

abstract class AbstractEntityAdapter extends AbstractAdapter implements EntityAdapterInterface
{
    public function __construct(EntityInterface $entity = NULL){
        $this->setAdaptee($entity);
    }
    
	public function setEntity(EntityInterface $entity){
        $this->setAdaptee($entity);
        return $this;
    }
    
    public function getEntity(){
        return $this->getAdaptee();
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