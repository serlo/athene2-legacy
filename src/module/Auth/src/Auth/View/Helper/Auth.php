<?php
namespace Auth\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Auth\Service\AuthServiceInterface;

class Auth extends AbstractHelper
{
    private $authService;
    
	/**
	 * @return the $authService
	 */
	public function getAuthService() {
		return $this->authService;
	}

	/**
	 * @param field_type $authService
	 */
	public function setAuthService(AuthServiceInterface $authService) {
		$this->authService = $authService;
	}

	/**
	 * @return AuthServiceInterface
	 */
	public function __invoke()
	{
		return $this->authService;
	}
}
