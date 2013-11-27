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
namespace RelatedContent\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class ExternalForm extends Form
{

    function __construct()
    {
        parent::__construct('external');
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'clearfix');
        $inputFilter = new InputFilter('external');
        $this->setInputFilter($inputFilter);
        
        $this->add(array(
            'name' => 'title',
            'type' => 'Text',
            'attributes' => array(),
            'options' => array(
                'label' => 'Title:'
            )
        ));
        
        $this->add(array(
            'name' => 'url',
            'type' => 'Zend\Form\Element\Url',
            'attributes' => array(
                'placeholder' => 'http://'
            ),
            'options' => array(
                'label' => 'url:'
            )
        ));
        
        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => 'Add',
                'class' => 'btn btn-success pull-right'
            )
        ));
        
        $inputFilter->add(array(
            'name' => 'title',
            'required' => true
        ));
        
        $inputFilter->add(array(
            'name' => 'url',
            'required' => true
        ));
    }
}