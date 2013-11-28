<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author	Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Blog\Form;

use Zend\InputFilter\InputFilter;
use Zend\Form\Form;

class PostForm extends Form
{

    function __construct()
    {
        parent::__construct('post');
        $this->setAttribute('method', 'post');
        $inputFilter = new InputFilter('post');
        
        $this->add(array(
            'name' => 'title',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
            ),
            'options' => array(
                'label' => 'Title:'
            )
        ));
        
        $this->add(array(
            'name' => 'content',
            'type' => 'Zend\Form\Element\Textarea',
            'attributes' => array(
                'class' => '',
            ),
            'options' => array(
                'label' => 'Content:'
            )
        ));
        
        $this->add(array(
            'name' => 'publish',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'datepicker',
            ),
            'options' => array(
                'label' => 'Publish Date:'
            )
        ));
        
        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => 'Speichern',
                'class' => 'btn btn-success pull-right'
            )
        ));
        
        $inputFilter->add(array(
            'name' => 'title',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'HtmlEntities'
                )
            )
        ));
        
        $inputFilter->add(array(
            'name' => 'content',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'HtmlEntities'
                )
            )
        ));
        
        $this->setInputFilter($inputFilter);
    }
}