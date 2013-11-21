<?php

/**
 * 
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace LearningResource\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class GroupedTextExerciseForm extends Form
{

    function __construct()
    {
        parent::__construct('grouped-text-exercise');
        $this->setAttribute('method', 'post');
        $inputFilter = new InputFilter('grouped-text-exercise');
        
        $this->add(array(
            'name' => 'content',
            'type' => 'Zend\Form\Element\Textarea',
            'attributes' => array(
            )
        ));
        
        $this->add(new Controls());
        
        $inputFilter->add(array(
            'name' => 'content',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'HtmlEntities'
                )
            )
        ));
        
        $this->setInputFilter($inputFilter);
    }
}