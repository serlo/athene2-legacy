<?php
/**
 * 
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Entity\Form\Revision;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

abstract class AbstractRevisionFieldset extends Fieldset implements InputFilterProviderInterface {
    public function __construct(){
        parent::__construct('revision');
    }
}