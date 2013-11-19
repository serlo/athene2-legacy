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
namespace Contexter\Form;

use Zend\Form\Fieldset;

class ParameterFieldset extends Fieldset
{

    public function __construct($key, $value)
    {
        parent::__construct('parameter');
        
        $this->add(array(
            'name' => $key,
            'type' => 'Zend\Form\Element\Checkbox',
            'attributes' => array(
                'checked' => true
            ),
            'options' => array(
                'value' => true,
                'label' => 'Match parameter:'
            )
        ));
        
        $this->add(array(
            'name' => uniqid(),
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'value' => $key . ': ' . $value,
                'class' => 'disabled',
                'disabled' => true
            )
        ));
    }
}