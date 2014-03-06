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
namespace Term\Form;

use Zend\Form\Element\Text;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

class TermFieldset extends Fieldset implements InputFilterProviderInterface
{

    function __construct()
    {
        parent::__construct('term');
        
        $this->add((new Text('name'))->setLabel('Name:'));
    }

    public function getInputFilterSpecification()
    {
        return [
            'name' => [
                'required' => true,
                'filters' => [
                    [
                        'name' => 'StripTags'
                    ]
                ],
                'validators' => [
                    [
                        'name'    => 'NotEmpty'
                    ],
                    [
                        'name'    => 'Regex',
                        'options' => [
                            'pattern' => '~^[a-zA-Z\-_ 0-9äöüÄÖÜß]+$~'
                        ]
                    ]
                ]
            ]
        ];
    }
}