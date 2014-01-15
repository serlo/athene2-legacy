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

class ArticleForm extends Form
{

    function __construct()
    {
        parent::__construct('article');
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'clearfix');
        $inputFilter = new InputFilter('article');

        $this->add((new Text('title'))->setAttribute('id', 'title')->setLabel('Title:'));
        $this->add((new Textarea('content'))->setAttribute('id', 'content')->setLabel('Content:'));
        $this->add(
            (new Textarea('reasoning'))->setAttribute('id', 'reasoning')->setLabel('Reasoning:')->setAttribute(
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

        $inputFilter->add(
            [
                'name'     => 'content',
                'required' => true
            ]
        );

        $this->setInputFilter($inputFilter);
    }
}
