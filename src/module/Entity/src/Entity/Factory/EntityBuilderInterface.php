<?php
namespace Entity\Factory;

use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Form\Form;

interface EntityBuilderInterface {
    /**
     * Builds the Entity and registers components like `taxonomy` or `repositories`
     * 
     * @param EntityFactoryInterface $entityService
     * @return $this
     */
	public function build(EntityFactoryInterface $entityService);
	
	/**
	 * Returns a hydrated ViewModel
	 * 
	 * @param string $name
	 * @return ViewModel
	 */
	public function toViewModel($name);
	
	/**
	 * Returns a hydrated JsonModel
	 * 
	 * @return JsonModel
	 */
	public function toJsonModel();
	
	/**
	 * @param JsonModel $_jsonModel
	 * @return $this
	 */
	public function setJsonModel (JsonModel $_jsonModel);
	
	/**
	 * Returns the JsonModel
	 * 
	 * @return JsonModel
	 */
	public function getJsonModel();
	
	/**
	 * Returns the ViewModel
	 * 
	 * @return ViewModel
	 */
	public function getViewModel();
	
	/**
	 * @param ViewModel $_viewModel
	 * @return $this
	 */
	public function setViewModel (ViewModel $_viewModel);
	
	/**
	 * @return string $_template
	 */
	public function getTemplate ($name);
	
	/**
	 * Returns the Form
	 * 
	 * @return Form $_form
	 */
	public function getForm ();
	
	/**
	 * Checks if a form is set
	 * 
	 * @return bool
	 */
	public function hasForm();
	
	public function hasTemplate($name);
	
	/**
	 * Sets a template
	 * 
	 * @param string $name
	 * @param string $_template
	 * @return $this
	 */
	public function setTemplate ($name, $template);
	
	/**
	 * Sets the Form
	 * 
	 * @param Form $_form
	 * @return $this
	 */
	public function setForm (Form $_form);
}