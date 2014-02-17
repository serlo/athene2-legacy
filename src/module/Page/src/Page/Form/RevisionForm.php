<?php
namespace Page\Form;

use Zend\Form\Element\Submit;
use Zend\Form\Element\Text;
use Zend\Form\Element\Textarea;
use Zend\Form\Form;

class RevisionForm extends Form
{

    public function __construct($entityManager)
    {
        parent::__construct('createRepository');
        $filter = new RevisionFilter($entityManager);
        
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form-horizontal');
        
        $this->setInputFilter($filter);
        $this->add((new Text('title'))->setLabel('Title:'))
            ->setAttribute('required', 'required');
        
        $this->add((new Textarea('content'))->setAttribute('class', 'ckeditor')
            ->setLabel('Content:'))
            ->setAttribute('required', 'required');
        
        $this->add((new Submit('submit'))->setValue('Save')
            ->setAttribute('class', 'btn btn-success pull-right'));
    }
}