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
namespace Subject\Application\Decorator;

use Subject\Service\SubjectServiceInterface;
use Subject\Application\Component\ComponentInterface;

abstract class AbstractDecorator implements SubjectServiceInterface
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
	public function setConcreteComponent(SubjectServiceInterface $concreteComponent) {
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
    }

    public function __construct (){
        $this->components = array();
    }

    public function providesMethod ($method)
    {
        if (method_exists($this, $method)) {
            return true;
        }
        if ($this->concreteComponent instanceof AbstractDecorator) {
            return $this->concreteComponent->providesMethod($method);
        }
        foreach($this->components as $component){
            if($component->providesMethod($method))
                return true;
        }
        return false;
    }
    
    public function addComponent(ComponentInterface $component){
        if($this->hasComponent($component))
            throw new \Exception('Component `'.get_class($component).'` already registered.');
        
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
    
    public function isImplementing($classname){
        // @TODO
    }
}