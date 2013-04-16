<?php
namespace Page\Form;

use Zend\Form\Form;

class Page extends Form
{

    function __construct ()
    {
        parent::__construct('page');
        $this->setAttribute('method', 'post');
        $this->setInputFilter(new PageFilter());

        /*$this->add(array(
        		'type' => 'Zend\Form\Element\Csrf',
        		'name' => 'security',
        		'options' => array(
        				'csrf_options' => array(
        						'timeout' => 86400
        				)
        		)
        ));*/
        
        $this->add(array(
        		'name' => 'title',
        		'attributes' => array(
    				'type' => 'text',
        		    'class' => 'input-xxlarge',
    		        'placeholder' => 'Titel',
        		),
        ));
        
        $this->add(array(
        		'name' => 'slug',
        		'attributes' => array(
    				'type' => 'text',
        		    'class' => 'input-xxlarge',
    		        'placeholder' => 'Slug (Beispiel: ueber-uns, finanzen, mathe-richtig-lernen)',
        		),
        ));
        
        $this->add(array(
        		'name' => 'content',
                'type' => 'Zend\Form\Element\Textarea',
                'attributes' => array(
                    'class' => 'ckeditor'
                )
        ));
        
        $this->add(array(
            'name' => 'submit',
            'label' => '',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Speichern',
                'class' => 'btn btn-success'
            ),
            'options' => array()
        ));
        
        $this->add(array(
            'name' => 'reset',
            'label' => '',
            'attributes' => array(
                'type' => 'reset',
                'value' => 'Verwerfen',
                'class' => 'btn',
            ),
            'options' => array(
            )
        ));
    }
}

?>