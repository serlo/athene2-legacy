<?php
namespace Auth\Service;

class AuthService implements AuthServiceInterface
{

    private $authService, $hashService, $adapter, $authResult;

    /**
	 * @return the $adapter
	 */
	public function getAdapter() {
		return $this->adapter;
	}

	/**
	 * @param field_type $adapter
	 */
	public function setAdapter($adapter) {
		$this->adapter = $adapter;
	}

	public function login ($username, $password)
    {
        $salt = '';
        
        $hostTable = new \Zend\Db\TableGateway\TableGateway('user', $this->adapter);
        $results = $hostTable->select(array(
            'username' => $username
        ));
        
        foreach ($results as $result)
            $hashedPassword = $result["password"];
        
        $salt = $this->hashService->find_salt($hashedPassword);
        
        $password = $this->hashService->hash_password($password, $salt);
        
        $this->authService->getAdapter()->setIdentity($username);
        $this->authService->getAdapter()->setCredential($password);
        return $this->authService->authenticate();
    }

    public function logout ()
    {
        if ($this->authService->hasIdentity())
            $this->authService->clearIdentity();
    }

    public function loggedIn ()
    {
        return $this->authService->hasIdentity();
    }

    public function hasRole ($role)
    {
    	
        
    }

    /**
     *
     * @return the $authService
     */
    public function getAuthService ()
    {
        return $this->authService;
    }

    /**
     *
     * @return the $hashService
     */
    public function getHashService ()
    {
        return $this->hashService;
    }

    /**
     *
     * @param field_type $authService            
     */
    public function setAuthService (\Zend\Authentication\AuthenticationService $authService)
    {
        $this->authService = $authService;
    }

    /**
     *
     * @param field_type $hashService            
     */
    public function setHashService (HashServiceInterface $hashService)
    {
        $this->hashService = $hashService;
    }
}
?>