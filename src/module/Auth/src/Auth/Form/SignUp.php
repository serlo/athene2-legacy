<?php
namespace Auth\Form;

use Zend\Form\Form;

class SignUp extends Form
{

    public function __construct ()
    {
        parent::__construct('signUp');
        
        $this->setAttribute('action', '/register');
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form-horizontal');
        $this->setInputFilter(new \Auth\Form\SignUpFilter());
        
        
        $this->add(array(
            'name' => 'username',
            'type' => 'text',
            'attributes' => array(
                'placeholder' => 'Wunschname',
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Benutzername:',
                array(
                    'twb' => array()
                )
            )
        ));
        
        $this->add(array(
            'name' => 'email',
            'type' => 'text',
            'attributes' => array(
                'placeholder' => 'user@beispiel.de',
                'type' => 'email',
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'E-Mail-Adresse:'
            )
        ));
        
        $this->add(array(
            'name' => 'emailConfirm',
            'type' => 'text',
            'attributes' => array(
                'placeholder' => 'user@beispiel.de',
                'type' => 'email',
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'E-Mail-Adresse bestätigen:'
            )
        ));
        
        $this->add(array(
            'name' => 'password',
            'type' => 'password',
            'attributes' => array(
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Passwort:'
            )
        ));
        
        $this->add(array(
            'name' => 'passwordConfirm',
            'type' => 'password',
            'attributes' => array(
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Passwort bestätigen:'
            )
        ));
        
        $this->add(array(
            'name' => 'tos',
            'type' => 'checkbox',
            'attributes' => array(
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Ich habe die Nutzungsbedingungen gelesen und verstanden und akzeptiere diese.',
                'unchecked_value' => ''
            )
        ));
        
        $this->add(array(
            'name' => 'submit',
            'label' => '',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Registrieren',
                'class' => 'btn btn-primary'
            ),
            'options' => array()
        ));
        
        $this->add(array(
            'name' => 'reset',
            'label' => '',
            'attributes' => array(
                'type' => 'reset',
                'value' => 'Zurücksetzen',
                'class' => 'btn btn-danger',
            ),
            'options' => array(
            )
        ));
    }
}
