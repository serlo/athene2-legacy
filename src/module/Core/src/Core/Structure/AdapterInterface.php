<?php

namespace Core\Structure;

interface AdapterInterface {

    /**
     * Returns the adaptee
     * 
	 * @return the $adaptee
	 */
	public function getAdaptee();

	/**
	 * Sets the adaptee
	 * 
	 * @param mixed $adaptee
	 */
	public function setAdaptee($adaptee);

	public function __construct($adaptee = NULL);
}