<?php
namespace Auth\Form;

use Zend\InputFilter\InputFilter;
use DoctrineModule\Validator\UniqueObject;

class SignUpFilter extends InputFilter
{
    use \Common\Traits\ObjectManagerAwareTrait;

    public function __construct ($objectManager)
    {        
        $this->add(array(
            'name' => 'email',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'EmailAddress'
                ),
                array(
                    'name' => 'DoctrineModule\Validator\UniqueObject',
                    'options' => array(
                        'object_repository' => $objectManager->getRepository('User\Entity\User'),
                        'fields' => 'email',
                        'object_manager' => $objectManager
                    )
                )
            )
        ));
        
        $this->add(array(
            'name' => 'username',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'DoctrineModule\Validator\UniqueObject',
                    'options' => array(
                        'object_repository' => $objectManager->getRepository('User\Entity\User'),
                        'fields' => 'username',
                        'object_manager' => $objectManager
                    )
                )
            )
        ));
        
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
            )
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