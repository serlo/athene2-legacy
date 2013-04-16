<?php
namespace Core\Structure;

abstract class AbstractDecorator
{

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
	}

	public function __call ($method, $args)
    {
        return call_user_func_array($this->concreteComponent, $method, $args);
    }

    public function __construct ($concreteComponent)
    {
        $this->concreteComponent = $concreteComponent;
    }

    public function providesMethod ($method)
    {
        if (method_exists($this, $method)) {
            return true;
        }
        if ($this->concreteComponent instanceof AbstractDecorator) {
            return $this->concreteComponent->providesMethod($method);
        }
        return false;
    }
}