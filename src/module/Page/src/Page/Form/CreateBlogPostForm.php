<?php
namespace Blog\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods;

class PostForm extends Form
{
  public function __construct($name = null)
  {
    parent::__construct('post');

    $this->setAttribute('method', 'post')
         ->setHydrator(new ClassMethods())
         ->setInputFilter(new InputFilter());

    $this->add(array(
      'type' => 'Blog\Form\PostFieldset',
      'options' => array(
        'use_as_base_fieldset' => true
      )
    ));

    $this->add(array(
      'name' => 'security',
      'type' => 'Zend\Form\Element\Csrf'
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
      'security',
      'post' => array(
        'title',
        'text'
      )
    ));
  }
}