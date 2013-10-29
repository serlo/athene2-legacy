<?php
namespace Page\Form;

use Zend\Form\Form;


class RepositoryForm extends Form
{
  public function __construct($objectManager)
  { 
    parent::__construct('createRepository');
    $filter = new CreateRepositoryFilter($objectManager);
    
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form-horizontal');
        
        $this->setInputFilter($filter);
        
        $this->add(array(
            'name' => 'language_id',
            'label' => '',
            'attributes' => array(
                'type' => 'hidden'
            ),
            'options' => array()
        ));
        
        
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
            'type' => 'Zend\Form\Element\MultiCheckbox',
            'name' => 'roles',
            'options' => array(
                'label' => 'Welche Rollen sollen die Seite bearbeiten können?',
                'value_options' => array(
                    '1' => 'Guest',
                    '2' => 'User',
                    '3' => 'Helper',
                    '4' => 'Moderator',
                    '5' => 'Admin',
                    '6' => 'Sysadmin'
                ),
            ),
        ));
        
       
     
        
    $this->add(array(
      'name' => 'submit',
      'attributes' => array(
        'class' => 'btn btn-mini btn-success',
        'type'  => 'submit',
        'value' => 'Go',
        'id'    => 'submitbutton'
      )
    ));

    $this->add(array(
        'name' => 'fallback',
        'type' => 'checkbox',
        'attributes' => array(
            
        ),
        'options' => array(
            'label' => 'Fallback Repository',
            'unchecked_value' => 'no'
        )
    ));

  }
}