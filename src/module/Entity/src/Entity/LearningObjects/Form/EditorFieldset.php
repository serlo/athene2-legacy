<?php
/**
 * 
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Entity\LearningObjects\Form;

use Zend\Form\Fieldset;

class EditorFieldset extends Fieldset
{

    function __construct ()
    {
        parent::__construct('editor');
        
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