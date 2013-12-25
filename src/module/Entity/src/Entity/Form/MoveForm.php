<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author	    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	    LGPL-3.0
 * @license	    http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft f√ºr freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Entity\Plugin\Link\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class MoveForm extends Form
{

    function __construct()
    {
        parent::__construct('move');
        $this->setAttribute('method', 'post');
        $filter = new InputFilter();
        $this->setInputFilter($filter);
        
        $this->add(array(
            'name' => 'from',
            'attributes' => array(
                'type' => 'hidden'
            )
        ));
        
        $this->add(array(
            'name' => 'to',
            'attributes' => array(
                'type' => 'text',
                'tabindex' => 1,
                'placeholder' => 'ID (e.g.: 123)'
            ),
            'options' => array(
                'label' => 'Move to: '
            )
        ));
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Move',
                'tabindex' => 2,
                'class' => 'btn btn-success pull-right'
            )
        ));
        
        $filter->add(array(
            'name' => 'from',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'int'
                )
            )
        ));
        
        $filter->add(array(
            'name' => 'to',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'int'
                ),
                array(
                    'name' => 'Common\Validator\NotIdentical',
                    'options' => array(
                        'token' => 'from'
                    )
                )
            )
        ));
    }
}