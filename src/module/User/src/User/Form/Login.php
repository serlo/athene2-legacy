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

class Login extends Form
{

    public function __construct()
    {
        parent::__construct('login');
        $this->setAttribute('method', 'post');
        $filter = new InputFilter();
        $this->setInputFilter($filter);
        
        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type' => 'text',
                'tabindex' => 1
            ),
            'options' => array(
                'label' => 'Email address:'
            )
        ));
        
        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type' => 'password',
                'tabindex' => 2
            ),
            'options' => array(
                'label' => 'Password:'
            )
        ));
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Log in',
                'tabindex' => 2,
                'class' => 'btn btn-success pull-right'
            )
        ));
        
        $filter->add(array(
            'name' => 'email',
            'required' => true
        ));
        
        $filter->add(array(
            'name' => 'password',
            'required' => true
        ));
    }
}