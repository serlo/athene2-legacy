<?php
namespace Page\Form;

use Zend\Form\Element\Submit;
use Zend\Form\Element\Text;
use Zend\Form\Element\Textarea;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class RevisionForm extends Form
{
    public function __construct()
    {
        parent::__construct('createRepository');
        $filter = new InputFilter();

        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form-horizontal');
        $this->setInputFilter($filter);
//        $this->setHydrator($hydrator);

        $text = new Text('title');
        $text->setLabel('Title:')->setAttribute('required', 'required')->setAttribute('id', 'title');
        $this->add($text);

        $textarea = new Textarea('content');
        $textarea->setLabel('Content:')->setAttribute('required', 'required')->setAttribute('id', 'content');
        $this->add($textarea);

        $submit = new Submit('submit');
        $submit->setValue('Save')->setAttribute('class', 'btn btn-success pull-right');
        $this->add($submit);
    }
}
