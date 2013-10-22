<?php
namespace Page\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods;

class RevisionForm extends Form
{
  public function __construct($objectManager)
  {
    parent::__construct('createRepository');
    $filter = new RevisionFilter($objectManager);
    
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form-horizontal');
        
        $this->setInputFilter($filter);
        $this->add(array(
            'name' => 'title',
            'type' => 'text',
            'attributes' => array(
                'placeholder' => 'Revision Title',
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Titel'
            )
        ));
        
        $this->add(array(
            'name' => 'content',
            'type' => 'Zend\Form\Element\Textarea',
            'attributes' => array(
                'class' => 'ckeditor',
                'placeholder' => 'Revision Content',
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Inhalt:'
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


  }
}