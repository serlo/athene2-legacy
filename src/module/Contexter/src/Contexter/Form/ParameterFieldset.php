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
use Zend\Form\Element\Checkbox;

class ParameterFieldset extends Fieldset
{

    public function __construct(array $parameters)
    {
        parent::__construct('parameters');
        
        $this->setLabel('Which parameters should be matched?');
        
        foreach ($parameters as $key => $value) {
            $this->add((new Checkbox($key))->setLabel('<strong>' . $key . ':</strong> ' . $value . '')
                ->setAttribute('checked', true));
        }
    }
}