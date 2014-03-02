<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Contexter\Form;

use Zend\Form\Element\Checkbox;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

class ParameterFieldset extends Fieldset implements InputFilterProviderInterface
{
    protected $parameters;

    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;

        parent::__construct('parameters');
        $this->setLabel('Which parameters should be matched?');

        foreach ($parameters as $key => $value) {
            if (is_array($value)) {
                $options = [];
                foreach ($value as $elem) {
                    $options[$elem] = $elem;
                }
                $this->add(
                    array(
                        'type'     => 'Zend\Form\Element\Select',
                        'options'  => array(
                            'disable_inarray_validator' => true,
                            'value_options'             => $options,
                            'empty_option'              => 'Ignore',
                            'label'                     => $key
                        ),
                        'required' => false,
                        'name'     => $key,
                    )
                );
            } else {
                $this->add(
                    (new Checkbox($key))->setLabel('<strong>' . $key . ':</strong> ' . $value)->setAttribute(
                        'checked',
                        true
                    )->setCheckedValue($value)
                );
            }
        }
    }

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getInputFilterSpecification()
    {
        $return = [];
        foreach ($this->parameters as $key => $value) {
            $return[$key] = [
                'required'    => false,
                'allow_empty' => true,
                'validators'  => [
                    [
                        'name'    => 'Regex',
                        'options' => [
                            'pattern' => '~^[a-zA-Z\-_\\\\/0-9]*$~'
                        ]
                    ]
                ]
            ];
        }

        return $return;
    }
}