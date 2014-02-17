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
namespace RelatedContent\Form;

use Zend\Form\Element\Submit;
use Zend\Form\Element\Text;
use Zend\Form\Element\Url;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class ExternalForm extends Form
{

    function __construct()
    {
        parent::__construct('external');
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'clearfix');
        $inputFilter = new InputFilter('external');
        $this->setInputFilter($inputFilter);
        
        $this->add((new Text('title'))->setLabel('Title:'));
        $this->add((new Url('url'))->setLabel('Url:'));
        
        $this->add((new Submit('submit'))->setValue('Add')
            ->setAttribute('class', 'btn btn-success pull-right'));
        
        $inputFilter->add([
            'name' => 'title',
            'required' => true,
            'filters' => [
                [
                    'name' => 'HtmlEntities'
                ]
            ]
        ]);
        
        $inputFilter->add([
            'name' => 'url',
            'required' => true,
            'filters' => [
                [
                    'name' => 'HtmlEntities'
                ]
            ]
        ]);
    }
}