<?php
namespace Core\Structure;

abstract class AbstractAdapter
{
    protected $adaptee;

    /**
	 * @return the $adaptee
	 */
	public function getAdaptee() {
		return $this->adaptee;
	}

	/**
	 * @param field_type $adaptee
	 */
	public function setAdaptee($adaptee) {
		$this->adaptee = $adaptee;
	}

	public function __construct($adaptee){
        $this->adaptee = $adaptee;
    }
}