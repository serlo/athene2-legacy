<?php
namespace Core\View\Helper;

use Zend\View\Helper\AbstractHelper;

class DateFormat extends AbstractHelper
{
    public function __invoke(){
        return 'd-m-Y H:i';
    }
}