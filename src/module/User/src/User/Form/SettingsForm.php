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
use Zend\Form\Element\Submit;
use Zend\Form\Element\Email;
use Zend\InputFilter\InputFilter;

class SettingsForm extends Form
{
    public function __construct($objectManager)
    {        
        parent::__construct('settings');
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'clearfix');
        $filter = new InputFilter();
        $this->setInputFilter($filter);
        
        $this->add((new Email('email'))->setLabel('Email:'));
        
        $this->add((new Submit('submit'))->setValue('Update')
            ->setAttribute('class', 'btn btn-success pull-right'));
        
        $filter->add(array(
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
            )
        ));
    }
}