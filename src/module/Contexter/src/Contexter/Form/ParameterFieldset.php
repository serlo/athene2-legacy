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
use Zend\InputFilter\InputFilterProviderInterface;

class ParameterFieldset extends Fieldset implements InputFilterProviderInterface
{

    public function __construct($key, $value)
    {
        $this->add(array(
            'name' => $key,
            'type' => 'Zend\Form\Element\Checkbox',
            'attributes' => array(
                'checked' => true
            ),
            'options' => array(
                'value' => $value,
                'label' => 'User parameter <strong>'.$key.'</strong> with value <strong>'.$value.'</strong>'           
            )
        ));
    }
    /*
     * (non-PHPdoc) @see \Zend\InputFilter\InputFilterProviderInterface::getInputFilterSpecification()
     */
    public function getInputFilterSpecification()
    {
        return array(
            /*'name' => array(
                'validators' => array()
                // validators for field "name"
                ,
                'filters' => array()
                // filters for field "name"
                
            )*/
        );
    }
}