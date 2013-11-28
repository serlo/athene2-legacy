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
namespace User\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class LostPassword extends Form
{

    public function __construct()
    {
        parent::__construct('lost-password');
        $this->setAttribute('method', 'post');
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);
        
        $this->add(array(
            'name' => 'password',
            'type' => 'password',
            'attributes' => array(
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'New password:'
            )
        ));
        
        $this->add(array(
            'name' => 'passwordConfirm',
            'type' => 'password',
            'attributes' => array(
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Repeat new password:'
            )
        ));
        
        $inputFilter->add(array(
            'name' => 'passwordConfirm',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'stringLength',
                    'options' => array(
                        'min' => 6
                    )
                ),
                array(
                    'name' => 'identical',
                    'options' => array(
                        'token' => 'password'
                    )
                )
            )
        ));
        
        $inputFilter->add(array(
            'name' => 'password',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'User\Authentication\HashFilter'
                )
            )
        ));
        
        $this->add(array(
            'name' => 'submit',
            'label' => '',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Update password',
                'class' => 'btn btn-success pull-right'
            ),
            'options' => array()
        ));
    }
}