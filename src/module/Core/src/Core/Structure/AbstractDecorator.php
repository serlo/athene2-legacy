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