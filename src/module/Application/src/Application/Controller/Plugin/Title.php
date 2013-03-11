<?php
namespace Application\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class Title extends AbstractPlugin
{
	public function set($title){
	    $layout = $this->getController()->layout();
        $layout->titleApplication = $title;
        $layout->titleHead = $title;
	}
}
