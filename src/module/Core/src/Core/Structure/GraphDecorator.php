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
namespace Core\Structure;

use Core\Component\ComponentInterface;

class GraphDecorator implements DecoratorInterface
{
    protected $components;
    
    protected $concreteComponent;

    /**
	 * @return the $concreteComponent
	 */
	public function getConcreteComponent() {
		return $this->concreteComponent;
	}

	/**
	 * @param field_type $concreteComponent
	 */
	public function setConcreteComponent($concreteComponent) {
		$this->concreteComponent = $concreteComponent;
		return $this;
	}

	public function __call ($method, $args)
    {        
        if($this->concreteComponent->providesMethod($method)){
            return call_user_func_array(array($this->concreteComponent, $method), $args);
        } else {
            foreach($this->components as $component){
                if($component->providesMethod($method)){
                    return call_user_func_array(array($component, $method), $args);                
                }
            }
        }
        
        throw new \Exception('Method `'.$method.'` not found.');
    }

    public function __construct (){
        $this->components = array();
    }

    public function providesMethod ($method)
    {
        $return = false;
        $return = (method_exists($this, $method));
        
        if ($this->concreteComponent instanceof AbstractDecorator) {
            $return = $return || $this->concreteComponent->providesMethod($method);
        }
        
        foreach($this->components as $component){
            $return = $return || $component->providesMethod($method);
        }
        
        return $return;
    }
    
    public function addComponent(ComponentInterface $component){
        if($this->hasComponent($component))
            throw new \Exception('Component `'.get_class($component).'` already registered.');
        
        foreach(get_class_methods($component) as $method)
            if($component->isMethodPublic($method) && $this->providesMethod($method))
                throw new \Exception("Fatal: Can't redeclare components `".get_class($component)."` method {$method}.");
        
        $this->components[$component->identify()] = $component;
    }
    
    public function hasComponent($component){
        // @TODO Check for already registered functions
        if($component instanceof  ComponentInterface){
            return isset($this->components[$component->identify()]);
        } elseif (is_string($component)) {
            return isset($this->components[$component]);
        } else {
            return new \InvalidArgumentException();
        }
        return false;
    }
    
    public function isInstanceOf($object){
        $return = ($this instanceof $object) || ($this->concreteComponent instanceof $object);
        foreach($this->components as $component){
            $return = $return || ($component instanceof $object);
        }
        return $return;
    }
}