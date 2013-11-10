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
namespace Upload\Form;

use Zend\Form\Form;

class UploadForm extends Form
{

    public function __construct()
    {
        parent::__construct('upload');
        $this->setAttribute('class', 'clearfix');
        
        $this->add(array(
            'name' => 'file',
            'type' => 'file',
            'attributes' => array(
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Upload file:'
            )
        ));
        
        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'class' => 'btn btn-success pull-right',
                'value' => 'Upload',
            ),
        ));
    }
}