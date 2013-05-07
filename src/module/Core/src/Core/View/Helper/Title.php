<?php
namespace Core\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Title extends AbstractHelper
{

    function __invoke ($title, $class = '')
    {
        echo '<header class="page-header '.$class.'"><h1>'.$title.'</h1></header>';
    }
}

?>