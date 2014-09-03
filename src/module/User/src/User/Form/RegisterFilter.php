<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace User\Form;

use Zend\InputFilter\InputFilter;
use DoctrineModule\Validator\UniqueObject;
use Zend\Validator\EmailAddress;
use Zend\Validator\GreaterThan;
use Zend\Validator\Identical;
use Zend\Validator\Regex;
use Zend\Validator\StringLength;

class RegisterFilter extends InputFilter
{
    use \Common\Traits\ObjectManagerAwareTrait;

    public function __construct($objectManager)
    {
        $this->add(
            [
                'name'       => 'email',
                'required'   => true,
                'validators' => [
                    [
                        'name' => 'EmailAddress',
                        'options' => [
                            'message' => 
                                'This does not appear to be a valid email address. Please choose a different one.'
                        ]
                    ],
                    [
                        'name'    => 'User\Validator\UniqueUser',
                        'options' => [
                            'object_repository' => $objectManager->getRepository('User\Entity\User'),
                            'fields'            => ['email'],
                            'object_manager'    => $objectManager,
                            'messages'          => [
                                UniqueObject::ERROR_OBJECT_NOT_UNIQUE =>
                                    'This email address is already in use. Please choose a different one.'
                            ]
                        ]
                    ]
                ]
            ]
        );

        $this->add(
            [
                'name'       => 'username',
                'required'   => true,
                'validators' => [
                    [
                        'name'    => 'User\Validator\UniqueUser',
                        'options' => [
                            'object_repository' => $objectManager->getRepository('User\Entity\User'),
                            'fields'            => ['username'],
                            'object_manager'    => $objectManager,
                            'messages'          => [
                                UniqueObject::ERROR_OBJECT_NOT_UNIQUE =>
                                    'This username is already taken. Please choose a different one.'
                            ]
                        ]
                    ],
                    [
                        'name'    => 'Regex',
                        'options' => [
                            'pattern'  => '~^[a-zA-Z\-\_0-9]+$~',
                            'messages' => [
                                Regex::NOT_MATCH =>
                                    'Your username may only contain'
                                    . ' letters, digits, underscores (_) and hyphens (-).'
                                    . ' Please choose a different one.'
                            ]
                        ]
                    ]
                ]
            ]
        );

        $this->add(
            [
                'name'       => 'emailConfirm',
                'required'   => true,
                'validators' => [
                    [
                        'name'    => 'Identical',
                        'options' => [
                            'token' => 'email',
                            'message' => 'The email addresses did not match. Please make sure they are identical.'
                        ]
                    ]
                ]
            ]
        );

        $this->add(
            [
                'name'       => 'passwordConfirm',
                'required'   => true,
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'min' => 6,
                            'message' => 'Your password needs to be at least 6 characters long.'
                        ]
                    ],
                    [
                        'name'    => 'Identical',
                        'options' => [
                            'token'   => 'password',
                            'message' => 'The passwords did not match. Please make sure they are identical.'
                        ]
                    ]
                ]
            ]
        );

        $this->add(
            [
                'name'     => 'password',
                'required' => true,
                'filters'  => [
                    [
                        'name' => 'Authentication\HashFilter'
                    ]
                ]
            ]
        );

        $this->add(
            [
                'name'       => 'tos',
                'required'   => true,
                'validators' => [
                    [
                        'name' => 'NotEmpty',
                    ],
                    [
                        'name'    => 'GreaterThan',
                        'options' => [
                            'min' => 0,
                            'messages' => [
                                GreaterThan::NOT_GREATER =>
                                    'Please confirm, that you have read, understood and accepted our terms of service.'
                            ]
                        ]
                    ]
                ]
            ]
        );
    }
}
