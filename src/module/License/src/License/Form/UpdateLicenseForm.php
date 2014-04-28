<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace License\Form;

use Zend\Form\Element\Select;
use Zend\Form\Element\Submit;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class UpdateLicenseForm extends Form
{
    public function __construct(array $licenses)
    {

        parent::__construct('context');
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'clearfix');
        $inputFilter = new InputFilter('context');
        $this->setInputFilter($inputFilter);

        $values = [];
        foreach ($licenses as $license) {
            $values[$license->getId()] = $license->getTitle();
        }

        $this->add((new Select('license'))->setLabel('Select a license:')->setValueOptions($values));

        $this->add(
            (new Submit('submit'))->setValue('Update')->setAttribute('class', 'btn btn-success pull-right')
        );
    }
}