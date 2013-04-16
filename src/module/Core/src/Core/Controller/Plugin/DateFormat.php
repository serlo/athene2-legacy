<?php
namespace Core\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class DateFormat extends AbstractPlugin
{
	public function __invoke(){
	    return 'd.m.Y H:i:s';
	}
}
