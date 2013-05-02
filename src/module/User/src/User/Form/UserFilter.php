<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */

namespace User\Form;

use Zend\InputFilter\InputFilter;

class UserFilter extends InputFilter
{

    public function __construct ()
    {
        $this->add(array(
            'name' => 'email',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StripTags'
                )
            ),
            'validators' => array(
                array(
                    'name' => 'EmailAddress'
                )
            )
        ));
        
        $this->add(array(
            'name' => 'password',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StripTags'
                )
            ),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 6
                    )
                )
            )
        ));
        
        $this->add(array(
            'name' => 'username',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StripTags'
                )
            )
        ));
        
        $this->add(array(
            'name' => 'givenname',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StripTags'
                )
            )
        ));
        
        $this->add(array(
            'name' => 'lastname',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StripTags'
                )
            )
        ));
        
        $this->add(array(
            'name' => 'gender',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StripTags'
                )
            )
        ));
    }
}