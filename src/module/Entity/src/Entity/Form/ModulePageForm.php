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
namespace Entity\Form;

use Zend\InputFilter\InputFilter;
use Zend\Form\Form;
use Zend\Form\Element\Text;
use Zend\Form\Element\Textarea;

class ModulePageForm extends Form
{

    function __construct()
    {
        parent::__construct('module-page');
        $this->setAttribute('method', 'post');
        $inputFilter = new InputFilter('module-page');
        $this->setAttribute('class', 'clearfix');

        $this->add((new Text('title'))->setLabel('Title:'));
        $this->add((new Textarea('content'))->setLabel('Content:'));
        $this->add((new Textarea('reasoning'))->setLabel('Reasoning:'));
        
        $this->add(new Controls());

        $inputFilter->add(array(
            'name' => 'title',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'HtmlEntities'
                )
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
            'name' => 'reasoning',
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