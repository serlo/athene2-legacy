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
namespace LearningResource\Form;

use Zend\InputFilter\InputFilter;
use Zend\Form\Form;

class ArticleForm extends Form
{

    function __construct()
    {
        parent::__construct('article');
        $this->setAttribute('method', 'post');
        $inputFilter = new InputFilter('text-solution');
        
        $this->add(array(
            'name' => 'title',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'class' => 'ckeditor'
            )
        ));
        
        $this->add(array(
            'name' => 'content',
            'type' => 'Zend\Form\Element\Textarea',
            'attributes' => array(
                'class' => 'ckeditor'
            )
        ));
        
        $this->add(new Controls());
        
        $inputFilter->add(array(
            'name' => 'title',
            'required' => true
        ));
        
        $inputFilter->add(array(
            'name' => 'content',
            'required' => true
        ));
        
        $this->setInputFilter($inputFilter);
    }
}