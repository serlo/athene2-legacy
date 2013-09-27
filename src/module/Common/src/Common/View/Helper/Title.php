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
namespace Common\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Title extends AbstractHelper
{

    function __invoke ($title, $class = '', $subTitle = false)
    {
        if($subTitle){
            $subTitle = ' <small>'.$subTitle.'</small>';
        }
        echo '<header class="page-header '.$class.'"><h1>'.$title.$subTitle.'</h1></header>';
    }
}