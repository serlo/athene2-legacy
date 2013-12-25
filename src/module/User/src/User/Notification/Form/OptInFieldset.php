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
use Zend\Form\Element;

class OptInFieldset extends Fieldset implements InputFilterProviderInterface
{

    public function __construct()
    {
        parent::__construct('subscription');
        
        $subscribe = new Element\Checkbox('subscribe');
        $subscribe->setName('subscribe');
        $subscribe->setOptions(array(
            'label' => 'Benachrichtigungen zu diesem Inhalt erhalten.'
        ));
        $subscribe->setChecked(true);
        
        $mailman = new Element\Checkbox('mailman');
        $mailman->setName('mailman');
        $mailman->setOptions(array(
            'label' => 'Benachrichtigungen auch als E-Mail erhalten.'
        ));
        $mailman->setChecked(true);
        
        $this->add($subscribe);
        $this->add($mailman);
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