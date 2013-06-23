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
namespace Issue\Form;

use Zend\Form\Form;

class IssueForm extends Form
{

    function __construct ()
    {
        parent::__construct('issue');
        $this->setAttribute('method', 'post');
        $this->setInputFilter(new IssueFilter());
        
        $this->add(array(
            'name' => 'title',
            'attributes' => array(
                'type' => 'text',
                'class' => 'input-xxlarge',
                'placeholder' => 'Titel',
            ),
        ));

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