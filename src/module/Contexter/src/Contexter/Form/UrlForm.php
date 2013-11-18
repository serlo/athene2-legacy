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
namespace Contexter\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class UrlForm extends Form
{
    public function __construct()
    {
        parent::__construct('url');
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'clearfix');
        $inputFilter = new InputFilter('url');
        
        $this->add(array(
            'name' => 'url',
            'type' => 'Text',
            'attributes' => array(),
            'options' => array(
                'label' => 'Url:'
            )
        ));
        
        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => 'Speichern',
                'class' => 'btn btn-success pull-right'
            )
        ));
        
        $inputFilter->add(array(
            'name' => 'title',
            'required' => true,
            'filters' => array()
        ));
        
        $inputFilter->add(array(
            'name' => 'content',
            'required' => true,
            'filters' => array()
        ));
    }
}