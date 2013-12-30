<?php


namespace Page\Form;

use Zend\Form\Form;

class RepositoryForm extends Form {
    protected $objectManager;
    
	public function __construct($objectManager) {
		parent::__construct ( 'createRepository' );
		$filter = new CreateRepositoryFilter ( $objectManager );
		$this->objectManager = $objectManager;
		$this->setAttribute ( 'method', 'post' );
		$this->setAttribute ( 'class', 'form-horizontal' );
		
		$this->setInputFilter ( $filter );
		
		
		$this->add ( array (
				'name' => 'slug',
				'type' => 'text',
				'attributes' => array (
						'class' => 'form-control',
						'placeholder' => 'Repository Url',
						'required' => 'required' 
				),
				'options' => array (
						'label' => 'Repository Url:' 
				) 
		) );
		
		$this->add ( array (
				'type' => 'Zend\Form\Element\MultiCheckbox',
				'name' => 'roles',
				'options' => array (
						'label' => 'Welche Benutzer sollen die Seite bearbeiten können?',
						'value_options' => $this->findRolesArray() 
				),
				'attributes' => array (
						'class' => 'form-control' 
				) 
		) );
		
		$this->add ( array (
				'name' => 'submit',
				'attributes' => array (
						'class' => 'btn btn-success',
						'type' => 'submit',
						'value' => 'Go',
						'id' => 'submitbutton' 
				) 
		) );
	}
	
	private function findRolesArray(){
	    $repository = $this->objectManager->getRepository('User\Entity\Role');
	    $roles = $repository->findAll();
	    $i = 1;
	    $array = array();
	    foreach ($roles as $role) {
	        $array[$i] = $role->getName();
	        $i++;
	    }
	    return $array;
	}
}