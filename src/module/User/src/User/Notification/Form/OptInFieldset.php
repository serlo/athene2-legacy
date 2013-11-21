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
namespace User\Notification\Form;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

class OptInFieldset extends Fieldset implements InputFilterProviderInterface
{

    public function __construct()
    {
        parent::__construct('subscription');
        
        $this->add(array(
            'name' => 'subscribe',
            'type' => 'Zend\Form\Element\Checkbox',
            'options' => array(
                'label' => 'Benachrichtigungen zu diesem Inhalt erhalten.',
                'checked' => true
            )
        ));
        
        $this->add(array(
            'name' => 'mailman',
            'type' => 'Zend\Form\Element\Checkbox',
            'options' => array(
                'label' => 'Benachrichtigungen auch als E-Mail erhalten.',
                'checked' => true
            )
        ));
    }

    public function getInputFilterSpecification()
    {
        return array(
            array(
                'name' => 'subscribe',
                'required' => true
            ),
            array(
                'name' => 'mailman',
                'required' => true
            )
        );
    }
}