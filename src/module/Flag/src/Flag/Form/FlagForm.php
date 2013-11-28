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
namespace Flag\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class FlagForm extends Form
{
    public function __construct(array $types){
        parent::__construct('context');
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'clearfix');
        $inputFilter = new InputFilter('context');
        $this->setInputFilter($inputFilter);
        
        $values = array();
        /* @var $type \Flag\Entity\TypeInterface */
        foreach($types as $type){
            $values[$type->getId()] = $type->getName();
        }
        
        $this->add(array(
            'name' => 'type',
            'type' => 'Select',
            'attributes' => array(),
            'options' => array(
                'label' => 'Type:',
                'value_options' => $values
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
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => 'Report',
                'class' => 'btn btn-success pull-right',
            )
        ));
        
        $inputFilter->add(array(
            'name' => 'content',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'HtmlEntities'
                )
            )
        ));
        
        $inputFilter->add(array(
            'name' => 'type',
            'required' => true,
        ));
    }
}