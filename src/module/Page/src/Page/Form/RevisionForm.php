<?php
namespace Page\Form;

use Zend\Form\Form;
use Zend\Form\Element\Text;
use Zend\Form\Element\Textarea;
use Zend\Form\Element\Submit;

class RevisionForm extends Form
{

    public function __construct($objectManager)
    {
        parent::__construct('createRepository');
        $filter = new RevisionFilter($objectManager);
        
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