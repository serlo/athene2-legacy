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
namespace LearningResource\Form;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use User\Notification\Form\OptInFieldset;

class Controls extends Fieldset implements InputFilterProviderInterface
{
    public function __construct()
    {
        parent::__construct('controls');


        $this->add(new OptInFieldset());


        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => 'Speichern',
                'class' => 'btn btn-success pull-right',
            )
        ));
    }

    public function getInputFilterSpecification()
    {
        return array();
    }
}