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
                        'name' => 'EmailAddress'
                    ],
                    [
                        'name'    => 'User\Validator\UniqueUser',
                        'options' => [
                            'object_repository' => $objectManager->getRepository('User\Entity\User'),
                            'fields'            => ['email'],
                            'object_manager'    => $objectManager
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
                            'object_manager'    => $objectManager
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
                        'name' => 'EmailAddress'
                    ],
                    [
                        'name'    => 'identical',
                        'options' => [
                            'token' => 'email'
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
                        'name'    => 'stringLength',
                        'options' => [
                            'min' => 6
                        ]
                    ],
                    [
                        'name'    => 'identical',
                        'options' => [
                            'token' => 'password'
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
                        'name'    => 'greaterThan',
                        'options' => [
                            'min' => 0
                        ]
                    ]
                ]
            ]
        );
    }
}
