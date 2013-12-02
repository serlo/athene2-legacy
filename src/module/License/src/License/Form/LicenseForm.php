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
namespace License\Form;

use Zend\Form\Form;
use License\Hydrator\LicenseHydrator;
use Zend\InputFilter\InputFilter;

class LicenseForm extends Form
{
    public function __construct()
    {
        parent::__construct('license');
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'clearfix');
        $this->setHydrator(new LicenseHydrator());
        $inputFilter = new InputFilter('license');
        $this->setInputFilter($inputFilter);
        
        $this->add(array(
            'name' => 'title',
            'type' => 'Text',
            'options' => array(
                'label' => 'Title:',
            )
        ));
        
        $this->add(array(
            'name' => 'content',
            'type' => 'Textarea',
            'options' => array(
                'label' => 'Content:',
            )
        ));
        
        $this->add(array(
            'name' => 'url',
            'type' => 'Zend\Form\Element\Url',
            'options' => array(
                'label' => 'License-Url:',
            )
        ));
        
        $this->add(array(
            'name' => 'iconHref',
            'type' => 'Zend\Form\Element\Url',
            'options' => array(
                'label' => 'Icon:',
            )
        ));
        
        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => 'Save',
                'class' => 'btn btn-success pull-right'
            )
        ));
        
        $inputFilter->add(array(
            'name' => 'title',
            'required' => true
        ));
        
        $inputFilter->add(array(
            'name' => 'iconHref',
            'required' => false
        ));
        
        $inputFilter->add(array(
            'name' => 'url',
            'required' => true
        ));
    }
}