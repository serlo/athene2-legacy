<?php
namespace Auth\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class Auth extends AbstractPlugin
{
    /**
     * @return AuthServiceInterface
     */
	public function __invoke()
	{
		return $this->getController()->getServiceLocator()->get('Auth\Service\AuthService');
	}
}
