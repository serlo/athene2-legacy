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
namespace Ads\Form;

use Zend\Form\Element\File;
use Zend\Form\Element\Select;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Text;
use Zend\Form\Element\Textarea;
use Zend\Form\Form;

class AdForm extends Form
{

    public function __construct()
    {
        parent::__construct('createAd');
        $this->setAttribute('class', 'clearfix');

        // $filter = new AdFilter();

        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form-horizontal');

        // $this->setInputFilter($filter);

        $this->add((new Text('title'))->setLabel('Title:'))->setAttribute('required', 'required');
        $this->add((new Text('url'))->setLabel('Url:'))->setAttribute('required', 'required');
        $this->add((new Textarea('content'))->setLabel('Content:'))->setAttribute('required', 'required');


        $this->add(
            (new Select('frequency'))->setValueOptions(
                array(
                    '0' => 'Never',
                    '1' => 'Less',
                    '2' => 'Normal',
                    '3' => 'More'
                )
            )->setAttribute('required', 'required')->setLabel('frequency')->setValue('2')
        );

        $this->add(
            (new File('file'))->setLabel('Bild hochladen')->setAttribute('required', 'required')
        );

        $this->add(
            (new Submit('submit'))->setValue('Save')->setAttribute('class', 'btn btn-success pull-right')
        );
    }
}