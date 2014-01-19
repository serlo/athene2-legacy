<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Discussion\Form;

use Notification\Form\OptInFieldset;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Textarea;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class CommentForm extends Form
{

    function __construct()
    {
        parent::__construct('comment');
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'clearfix');
        $inputFilter = new InputFilter('comment');

        $this->add((new Textarea('content'))->setLabel('Content:'));

        $this->add(new OptInFieldset());

        $this->add(
            (new Submit('submit'))->setValue('Comment')->setAttribute('class', 'btn btn-success pull-right')
        );

        $inputFilter->add(
            array(
                'name'     => 'content',
                'required' => true,
                'filters'  => array(
                    array(
                        'name' => 'HtmlEntities'
                    )
                )
            )
        );

        $this->setInputFilter($inputFilter);
    }
}