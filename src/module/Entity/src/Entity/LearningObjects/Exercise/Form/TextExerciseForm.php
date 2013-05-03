<?php
/**
 * 
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Entity\LearningObjects\Exercise\Form;

use Zend\Form\Form;
use Entity\LearningObjects\Exercise\Filter\TextExerciseFilter;

class TextExerciseForm extends Form
{

    function __construct ()
    {
        parent::__construct('page');
        $this->setAttribute('method', 'post');
        $this->setInputFilter(new TextExerciseFilter());
        
        $this->add(array(
        		'name' => 'content',
                'type' => 'Zend\Form\Element\Textarea',
                'attributes' => array(
                    'class' => 'ckeditor'
                )
        ));
        
        $this->add(array(
            'name' => 'submit',
            'label' => '',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Speichern',
                'class' => 'btn btn-success'
            ),
            'options' => array()
        ));
        
        $this->add(array(
            'name' => 'reset',
            'label' => '',
            'attributes' => array(
                'type' => 'reset',
                'value' => 'Verwerfen',
                'class' => 'btn',
            ),
            'options' => array(
            )
        ));
    }
}