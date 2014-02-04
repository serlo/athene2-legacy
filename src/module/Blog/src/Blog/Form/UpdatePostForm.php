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
namespace Blog\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Element\DateTime;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Text;
use Zend\Form\Element\Textarea;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class UpdatePostForm extends Form
{

    function __construct(ObjectManager $objectManager)
    {
        parent::__construct('post');

        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'clearfix');

        $hydrator    = new DoctrineHydrator($objectManager);
        $inputFilter = new InputFilter('post');

        $this->setInputFilter($inputFilter);
        $this->setHydrator($hydrator);

        $this->add(
            array(
                'type'    => 'Common\Form\Element\ObjectHidden',
                'name'    => 'author',
                'options' => array(
                    'object_manager' => $objectManager,
                    'target_class'   => 'User\Entity\User'
                )
            )
        );

        $this->add((new Text('title'))->setAttribute('id', 'title')->setLabel('Title:'));
        $this->add((new Textarea('content'))->setAttribute('id', 'content')->setLabel('Content:'));
        $this->add(
            (new DateTime('publish'))->setAttribute('id', 'publish')->setLabel('Publish date:')->setAttribute(
                'class',
                'datepicker'
            )
        );
        $this->add((new Submit('submit'))->setValue('Save')->setAttribute('class', 'btn btn-success pull-right'));

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
                'name'     => 'author',
                'required' => true
            )
        );

        $inputFilter->add(
            array(
                'name'     => 'content',
                'required' => true
            )
        );
    }
}