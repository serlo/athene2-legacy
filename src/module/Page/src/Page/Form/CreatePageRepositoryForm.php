<?php
namespace Page\Form;

use Zend\Form\Form;


class CreatePageRepositoryForm extends Form
{
  public function __construct($objectManager)
  {
    parent::__construct('createRepository');
    $filter = new CreateRepositoryFilter($objectManager);
    
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form-horizontal');
        
        $this->setInputFilter($filter);
        $this->add(array(
            'name' => 'slug',
            'type' => 'text',
            'attributes' => array(
                'placeholder' => 'Repository Name',
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Repository Slug:'
            )
        ));

    $this->add(array(
      'name' => 'submit',
      'attributes' => array(
        'type'  => 'submit',
        'value' => 'Go',
        'id'    => 'submitbutton'
      )
    ));

    $this->setValidationGroup(array(

      'post' => array(
        'repositoryname'
      )
    ));
  }
}