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
namespace Attachment\Form;

use Zend\Form\Element\Submit;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class AttachmentForm extends Form implements AttachmentFieldsetProvider
{
    public function __construct()
    {
        parent::__construct('upload');

        $filter = new InputFilter();

        $this->setInputFilter($filter);
        $this->setAttribute('class', 'clearfix');
        $this->add(new AttachmentFieldset());
        $this->add((new Text('type'))->setLabel('Set type:'));
        $this->add(
            (new Submit('submit'))->setValue('Upload')->setAttribute('class', 'btn btn-success pull-right')
        );
    }
}
