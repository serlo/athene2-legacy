<?php
namespace Auth\Form;

use User\Form\UserFilter;
use Zend\InputFilter\InputFilter;

class SignUpFilter extends InputFilter
{

    public function __construct ()
    {
        $this->add(array(
            'name' => 'emailConfirm',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'EmailAddress'
                ),
                array(
                    'name' => 'identical',
                    'options' => array(
                        'token' => 'email'
                    )
                )
            )
        ));
        
        $this->add(array(
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
            ),
        ));
        
        $this->add(array(
            'name' => 'password',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'Auth\Filter\HashFilter'
                )
            )
        ));
        
        $this->add(array(
            'name' => 'tos',
            'required' => true
        ));
    }
}