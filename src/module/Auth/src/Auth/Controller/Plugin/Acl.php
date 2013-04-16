<?php
namespace Auth\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class Acl extends AbstractPlugin
{
    /**
     * @return \Zend\Permissions\Acl\Acl
     */
	public function __invoke()
	{
		return $this->getController()->getServiceLocator()->get('Zend\Permissions\Acl\Acl');
	}
}
