<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author	    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	    LGPL-3.0
 * @license	    http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft f√ºr freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Entity\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Form\Element\Textarea;

class TextExerciseForm extends Form
{

    function __construct()
    {
        parent::__construct('text-exercise');
        $this->setAttribute('method', 'post');
        $inputFilter = new InputFilter('text-exercise');
        $this->setAttribute('class', 'clearfix');
        
        $this->add((new Textarea('content'))->setLabel('Content:'));
        
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