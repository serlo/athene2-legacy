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

class TextExerciseGroupForm extends Form
{

    function __construct()
    {
        parent::__construct('text-exercise-group');
        $this->setAttribute('method', 'post');
        $inputFilter = new InputFilter('text-exercise-group');
        
        $this->add(array(
            'name' => 'content',
            'type' => 'Zend\Form\Element\Textarea',
            'attributes' => array(
                'class' => 'ckeditor'
            )
        ));
        
        $this->add(new Controls());
        
        $inputFilter->add(array(
            'name' => 'content',
            'required' => true
        ));
        
        $this->setInputFilter($inputFilter);
    }
}