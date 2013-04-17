<?php
namespace Entity\Adapter;

use Entity\Service\EntityServiceInterface;
use Core\Entity\AbstractEntityAdapter;

class EntityAdapter extends AbstractEntityAdapter {
	
	/**
	 * @var EntityServiceInterface
	 */
	protected $adaptee;
	
	/**
	 * @param EntityServiceInterface $prototype
	 * @return $this
	 */
	public function __construct(EntityServiceInterface $adaptee = NULL){
		$this->adaptee = $adaptee;
		return this;
	}

	/**
	 * @param EntityServiceInterface $prototype
	 * @return $this
	 */
	public function setEntity(EntityServiceInterface $adaptee = NULL){
		$this->adaptee = $adaptee;		
		return this;
	}
	
	/**
	 * @return EntityServiceInterface
	 */
	public function getEntity(){
		if($this->adaptee === NULL)
			throw new \Exception('Entity not set!');
		return $this->adaptee;
	}

	/**
	 * @return $this
	 */
	public function unsetEntity(){
		$entity = $this->adaptee;
		unset($entity);
		$this->adaptee = null;
		return this;
	}
	
	public function persist(){
		$this->getEntity()->persist();
		return $this;
	}
	
	public function kill(){
		$this->getEntity()->kill();
		return $this;
	}
}