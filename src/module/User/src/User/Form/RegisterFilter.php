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

use Zend\InputFilter\InputFilter;

class RegisterFilter extends InputFilter
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
                    'name' => 'User\Validator\UniqueUser',
                    'options' => array(
                        'object_repository' => $objectManager->getRepository('User\Entity\User'),
                        'fields' => array('email'),
                        'object_manager' => $objectManager
                    )
                )
            ),
            'filters' => array(
                array(
                    'name' => 'HtmlEntities'
                )
            )
        ));
        
        $this->add(array(
            'name' => 'username',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'User\Validator\UniqueUser',
                    'options' => array(
                        'object_repository' => $objectManager->getRepository('User\Entity\User'),
                        'fields' => array('username'),
                        'object_manager' => $objectManager
                    )
                )
            ),
            'filters' => array(
                array(
                    'name' => 'HtmlEntities'
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
                    'name' => 'User\Authentication\HashFilter'
                )
            )
        ));
        
        $this->add(array(
            'name' => 'tos',
            'required' => true
        ));
    }
}