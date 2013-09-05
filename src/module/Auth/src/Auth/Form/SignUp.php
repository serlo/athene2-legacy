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
namespace Auth\Form;

use User\Form\UserForm;

class SignUp extends UserForm
{

    public function __construct ($objectManager)
    {
        $filter = new \Auth\Form\SignUpFilter($objectManager);
        
        parent::__construct('signUp');
        
        $this->setAttribute('action', '/register');
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form-horizontal');
        $this->setInputFilter($filter);
        
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
            'name' => 'givenname',
            'type' => 'text',
            'options' => array(
                'label' => 'Vorname:'
            )
        ));
        
        $this->add(array(
            'name' => 'lastname',
            'type' => 'text',
            'options' => array(
                'label' => 'Nachname:'
            )
        ));
        
        $this->add(array(
            'name' => 'gender',
            'type' => 'select',
            'options' => array(
                'label' => 'Geschlecht:',
                'value_options' => array(
                    'n' => 'Keine Angabe',
                    'm' => 'Männlich',
                    'w' => 'Weiblich'
                )
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
                'class' => 'btn btn-danger'
            ),
            'options' => array()
        ));
    }
}
