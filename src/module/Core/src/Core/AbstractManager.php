<?php

namespace Core;

abstract class AbstractManager {
	protected $_instances = array();
	
	/**
	 * Adds an instance
	 * 
	 * @param string $name
	 * @param object $instance
	 * @throws \Exception
	 * @return $this
	 */
	protected function _addInstance($name, $instance){
		if(! is_object($instance) )
			throw new \Exception('Please pass only instances.');
		
		if($this->_hasInstance($name)){
			$instance = $this->_instances[$name];
			unset($instance);
			unset($this->_instances[$name]);
		}
		
		$this->_instances[$name] = $instance;
		return $this;
	}
	
	/**
	 * Checks if an instance is already registered
	 * 
	 * @param string $name
	 * @return boolean
	 */
	protected function _hasInstance($name){
		return array_key_exists($name, $this->_instances);
	}
	
	/**
	 * Returns an instance
	 * 
	 * @param string $name
	 * @throws \Exception
	 * @return multitype:
	 */
	protected function _getInstance($name){
		if(! $this->_hasInstance($name) )
			throw new \Exception('Instance `'.$name.'` not set.');
		
		return $this->_instances[$name];
	}
}