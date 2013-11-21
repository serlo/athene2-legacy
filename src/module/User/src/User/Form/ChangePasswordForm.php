<?php
namespace User\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
class ChangePasswordForm extends Form
{
    public function __construct()
    {        
        parent::__construct('settings');
        $this->setAttribute('method', 'post');

        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        $this->add(array(
            'name' => 'currentPassword',
            'type' => 'password',
            'attributes' => array(
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Current password:'
            )
        ));
        
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
                'value' => 'Update',
                'class' => 'btn btn-success pull-right'
            ),
            'options' => array()
        ));
    }
}