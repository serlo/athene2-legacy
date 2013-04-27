<?php
namespace Core\Structure;

abstract class AbstractAdapter implements AdapterInterface
{
    protected $adaptee;
    
	public function getAdaptee() {
		return $this->adaptee;
	}
	
	public function setAdaptee($adaptee) {
		$this->adaptee = $adaptee;
	}

	public function __construct($adaptee = NULL){
        $this->adaptee = $adaptee;
    }
}