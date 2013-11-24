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