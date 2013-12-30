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
namespace Flag\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Form\Element\Select;
use Zend\Form\Element\Textarea;
use Zend\Form\Element\Submit;
use Doctrine\Common\Collections\Collection;

class FlagForm extends Form
{

    public function __construct(Collection $types)
    {
        parent::__construct('context');
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'clearfix');
        $inputFilter = new InputFilter('context');
        $this->setInputFilter($inputFilter);
        
        $values = array();
        /* @var $type \Flag\Entity\TypeInterface */
        foreach ($types as $type) {
            $values[$type->getId()] = $type->getName();
        }
        
        $this->add((new Select('type'))->setLabel('Type:')
            ->setOptions(array(
            'value_options' => $values
        )));
        
        $this->add((new Textarea('content'))->setLabel('Content:'));
        
        $this->add((new Submit('submit'))->setValue('Report')
            ->setAttribute('class', 'btn btn-success pull-right'));
        
        $inputFilter->add(array(
            'name' => 'content',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'HtmlEntities'
                )
            )
        ));
        
        $inputFilter->add(array(
            'name' => 'type',
            'required' => true
        ));
    }
}