<?php
namespace Entity\Factory;

use Entity\Service\EntityServiceInterface;

abstract class AbstractEntityFactory extends EntityServiceProxy implements EntityFactoryInterface {	
	/**
	 * @param EntityServiceInterface $adaptee
	 * @return $this
	 */
	public function build(EntityServiceInterface $adaptee){
		$this->setSource($adaptee);
		
	    $this->uniqueName = 'Entity('.$adaptee->getId().')';
	    $this->_loadComponents();
		return $this;
	}
	
	abstract protected function _loadComponents();
	
	public function __construct(){
		
	}
}