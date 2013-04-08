<?php
namespace Page\Form;

use Zend\InputFilter\InputFilter;

class PageFilter extends InputFilter
{

    function __construct ()
    {
        $this->add(array(
            'name' => 'title',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                )
            )
        ));
        
        $this->add(array(
            'name' => 'slug',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                )
            )
        ));
        
        $this->add(array(
            'name' => 'content',
            'required' => true
        ));
    }
}

?>