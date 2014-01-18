<?php


namespace Page\Form;

use Zend\Form\Form;
use Zend\Form\Element\Text;
use Zend\Form\Element\Submit;
use Zend\Form\Element\MultiCheckbox;

class RepositoryForm extends Form {
    protected $objectManager;
    
	public function __construct($objectManager) {
		parent::__construct ( 'createRepository' );
		$filter = new CreateRepositoryFilter ( $objectManager );
		$this->objectManager = $objectManager;
		$this->setAttribute ( 'method', 'post' );
		$this->setAttribute ( 'class', 'form-horizontal' );
		$this->setInputFilter ( $filter );
        $this->add((new Text('slug'))->setLabel('Url:'));
        $this->add((new MultiCheckbox('roles'))->setValueOptions($this->findRolesArray())->setLabel('Roles:'));
        $this->add((new Submit('submit'))->setValue('Save')->setAttribute('class', 'btn btn-success pull-right'));
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