<?php
/**
 * 
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Entity\LearningObjects\Form;

use Zend\InputFilter\InputFilter;

class ContentFilter extends InputFilter
{

    function __construct ()
    {
        $this->add(array(
            'name' => 'content',
            'required' => true,
        ));
    }
}