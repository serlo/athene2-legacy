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
namespace Taxonomy\Form;

use Zend\Form\Form;
use Term\Form\TermFieldset;
use Zend\InputFilter\InputFilter;

class TermForm extends Form
{

    function __construct()
    {
        parent::__construct('term_taxonomy');
        $this->setAttribute('method', 'post');
        $this->setInputFilter(new InputFilter());
        
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden'
            ),
        ));
        
        $this->add(array(
            'name' => 'parent',
            'attributes' => array(
                'type' => 'hidden'
            ),
        ));
        
        $this->add(array(
            'name' => 'taxonomy',
            'attributes' => array(
                'type' => 'hidden'
            ),
        ));
        
        $this->add(array(
            'name' => 'weight',
            'attributes' => array(
                'type' => 'hidden'
            ),
        ));
        
        $this->add(new TermFieldset());
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Speichern',
                'class' => 'btn btn-success pull-right'
            ),
        ));
    }
}