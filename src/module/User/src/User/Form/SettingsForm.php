<?php
namespace User\Form;

use Zend\Form\Form;

class SettingsForm extends Form
{
    public function __construct()
    {        
        parent::__construct('settings');
        $this->setAttribute('method', 'post');
        
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
                'value' => 'Update',
                'class' => 'btn btn-success pull-right'
            ),
            'options' => array()
        ));
    }
}