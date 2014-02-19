<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author         Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license        LGPL-3.0
 * @license        http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link           https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright      Copyright (c) 2013 Gesellschaft f√ºr freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Entity\Form;

use Zend\Form\Element\Text;
use Zend\Form\Element\Textarea;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class ModuleForm extends Form
{

    function __construct()
    {
        parent::__construct('module');
        $this->setAttribute('method', 'post');
        $inputFilter = new InputFilter('module');
        $this->setAttribute('class', 'clearfix');

        $this->add((new Text('title'))->setLabel('Title:'));
        $this->add(
            (new Textarea('reasoning'))->setLabel('Reasoning:')->setAttribute(
                'class',
                'plain'
            )
        );

        $this->add(new Controls());

        $inputFilter->add(
            [
                'name'     => 'title',
                'required' => true,
                'filters'  => [
                    [
                        'name' => 'HtmlEntities'
                    ]
                ]
            ]
        );

        $inputFilter->add(
            [
                'name'     => 'reasoning',
                'required' => false,
                'filters'  => [
                    [
                        'name' => 'HtmlEntities'
                    ]
                ]
            ]
        );

        $this->setInputFilter($inputFilter);
    }
}