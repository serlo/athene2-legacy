<?php
namespace Entity\LearningObjects\Form\Revision;

class RevisionWithContentFieldset extends AbstractRevisionFieldset
{
    function __construct ()
    {
        parent::__construct();
        
        $this->add(array(
            'name' => 'content',
            'type' => 'Zend\Form\Element\Textarea',
            'attributes' => array(
                'class' => 'ckeditor'
            )
        ));
    }
    
    public function getInputFilterSpecification(){
        return array(
            'content' => array(
                'required' => true,
            ),
        );
    }
}