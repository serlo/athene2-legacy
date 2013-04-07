<?php
namespace Auth\Form;

use Zend\Form\Form;

class Login extends Form
{

    public function __construct ()
    {
        parent::__construct('login');
        $this->setAttribute('action', '/login');
        $this->setAttribute('method', 'post');
        $this->setInputFilter(new \Auth\Form\LoginFilter());
        
        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'E-Mail-Adresse:'
            )
        ));
        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type' => 'password'
            ),
            'options' => array(
                'label' => 'Password:'
            )
        ));
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Einloggen'
            )
        ));
    }
}