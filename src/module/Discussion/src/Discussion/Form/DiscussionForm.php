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
use Zend\Form\Element\Text;
use Zend\Form\Element\Textarea;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class DiscussionForm extends Form
{

    function __construct()
    {
        parent::__construct('discussion');
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'clearfix');

        $inputFilter = new InputFilter('discussion');

        $this->add(
            array(
                'name'       => 'forum',
                'type'       => 'Zend\Form\Element\Hidden',
                'attributes' => array()
            )
        );

        $this->add((new Text('title'))->setLabel('Title:'));
        $this->add((new Textarea('content'))->setLabel('content:'));

        $this->add(new OptInFieldset());

        $this->add(
            (new Submit('submit'))->setValue('Start discussion')->setAttribute('class', 'btn btn-success pull-right')
        );

        $inputFilter->add(
            array(
                'name'     => 'forum',
                'required' => true
            )
        );

        $inputFilter->add(
            array(
                'name'     => 'title',
                'required' => true,
                'filters'  => array(
                    array(
                        'name' => 'HtmlEntities'
                    )
                )
            )
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