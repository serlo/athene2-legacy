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
namespace Ads\Form;

use Zend\Form\Form;
use Ads\Form\AdFilter;
use Zend\Form\Element\File;
use Zend\Form\Element\Submit;

class AdForm extends Form
{

    public function __construct()
    {
        parent::__construct('createAd');
        $this->setAttribute('class', 'clearfix');
        
        $filter = new AdFilter();
        
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form-horizontal');
        
        $this->setInputFilter($filter);
        $this->add(array(
            'name' => 'title',
            'type' => 'text',
            'attributes' => array(
                'placeholder' => 'Add Title',
                'required' => 'required',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Titel'
            )
        ));
        
        $this->add(array(
            'name' => 'content',
            'type' => 'Zend\Form\Element\Textarea',
            'attributes' => array(
                'class' => 'ckeditor',
                'placeholder' => 'Add Content',
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Inhalt'
            )
        ));
        
        $this->add(array(
            'name' => 'frequency',
            'type' => 'text',
            'attributes' => array(
                'placeholder' => 'Add Frequency',
                'required' => 'required',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Frequenz'
            )
        ));
        
        $this->add((new File('file'))->setLabel('Bild hochladen')
            ->setAttribute('required', 'required'));
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'class' => 'btn btn-success',
                'type' => 'submit',
                'value' => 'erstellen',
                'id' => 'submitbutton'
            )
        ));
    }
}