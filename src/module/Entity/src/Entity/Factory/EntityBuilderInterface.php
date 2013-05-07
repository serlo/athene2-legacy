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
	
	/**
	 * Sets the Form
	 * 
	 * @param Form $_form
	 * @return $this
	 */
	public function setForm (Form $_form);
	
	/**
	 * @return ViewModel
	 */
	public function toViewModel();
	
	public function getRepositoryComponent();
}