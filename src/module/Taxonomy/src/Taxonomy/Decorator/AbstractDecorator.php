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
namespace Taxonomy\Decorator;

use Taxonomy\Controller\AbstractController;
use Zend\Form\Form;
use Taxonomy\Exception\InvalidArgumentException;
use Core\Structure\GraphDecorator;

abstract class AbstractDecorator extends GraphDecorator {

	/**
	 * @var AbstractController
	 */
	protected $controller;
	
	/**
	 * @var Form
	 */
	protected $form;
	
	/**
	 * @return \Taxonomy\Controller\AbstractController $controller
	 */
	public function getController() {
		return $this->controller;
	}

	/**
	 * @param \Taxonomy\Controller\AbstractController $controller
	 * @return $this
	 */
	public function setController(AbstractController $controller) {
		$this->controller = $controller;
		return $this;
	}

	/**
	 * @return \Zend\Form\Form $form
	 */
	public function getForm ()
	{
		$form = $this->form;
		$form->setData($this->toArray());
		return $form;
	}
	
	/**
	 * @param \Zend\Form\Form $form
	 * @return $this
	 */
	public function setForm (Form $form)
	{
		$this->form = $form;
		return $this;
	}
	
    public function getRoute(){
        return $this->getController()->getRoute();
    }
    
    public function hasController(){
    	return is_object($this->getController());
    }
    
    public function getViewModel($methodName){
        if(!method_exists($this->getController(), $methodName))
            throw new InvalidArgumentException('Controller of class `' . get_class($this->getController()). '` does not know a method called `' . $methodName . '`');
            
        return $this->getController()->$methodName($this->getId());
    }
}