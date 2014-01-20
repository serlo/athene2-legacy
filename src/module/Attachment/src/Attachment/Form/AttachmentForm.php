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

use Zend\Form\Element\File;
use Zend\Form\Element\Submit;
use Zend\Form\Form;

class AttachmentForm extends Form
{
    public function __construct()
    {
        parent::__construct('upload');
        $this->setAttribute('class', 'clearfix');

        $this->add((new File('file'))->setLabel('Upload file:'));
        $this->add(
            (new Submit('submit'))->setValue('Upload')->setAttribute('class', 'btn btn-success pull-right')
        );
    }
}
