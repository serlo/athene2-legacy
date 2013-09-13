<?php

/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author	Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Core\Decorator;

use Core\Component\ComponentInterface;

abstract class GraphDecorator implements DecoratorInterface, GraphDecoratorInterface {
	protected $components;
	abstract public function getInheritableMethods();
	public function inheritsMethod($method) {
		return in_array ( $method, $this->getInheritableMethods () );
	}
	public function __call($method, $args) {
		foreach ( $this->components as $component ) {
			if ($component->providesMethod ( $method )) {
				return call_user_func_array ( array (
						$component,
						$method 
				), $args );
			}
		}
		
		throw new \Exception ( 'Method `' . $method . '` not found.' );
	}
	public function __construct() {
		$this->components = array ();
	}
	public function providesMethod($method) {
		$return = false;
		$return = $this->inheritsMethod ( $method ) && (method_exists ( $this, $method ));
		
		foreach ( $this->components as $component ) {
			if ($component instanceof DecoratorInterface) {
				$return = $return || $component->providesMethod ( $method );
			} else {
				$return = $return || method_exists ( $component, $method );
			}
		}
		
		return $return;
	}
	public function addComponent($component, $name = 'concrete') {
		if ($this->hasComponent ( $name ))
			throw new \Exception ( 'Component `' . $name . '` `' . get_class ( $component ) . '` already registered.' );
		
		foreach ( get_class_methods ( $component ) as $method ) {
			if (($component instanceof GraphDecoratorInterface && $component->inheritsMethod ( $method )) && $this->providesMethod ( $method ))
				throw new \Exception ( "Fatal: Can't redeclare components `" . get_class ( $component ) . "` method {$method}." );
		}
		
		$this->components [$name] = $component;
	}
	public function hasComponent($component) {
		if ($component instanceof ComponentInterface) {
			return isset ( $this->components [$component->identify ()] );
		} elseif (is_string ( $component )) {
			return isset ( $this->components [$component] );
		} else {
			return new \InvalidArgumentException ();
		}
		return false;
	}
	public function providesComponent($name){
		return $this->hasComponent($name);
	}
	
	public function isInstanceOf($object) {
		$return = ($this instanceof $object);
		foreach ( $this->components as $component ) {
			$return = $return || ($component instanceof $object);
		}
		return $return;
	}
}