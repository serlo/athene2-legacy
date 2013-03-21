<?php
namespace Auth\Service;

use Zend\Permissions\Acl\Acl;
use User\Entity\User;
use Doctrine\ORM\EntityManager;
use Zend\Permissions\Acl\Role\RoleInterface;

class AuthService implements AuthServiceInterface
{

    private $authService, $hashService, $adapter, $authResult, $aclService;
    private $entityManager;
    private $user;

    /**
	 * @return the $user
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * @param User $user
	 */
	public function setUser(User $user) {
		$this->user = $user;
	}

	/**
	 * @return the $entityManager
	 */
	public function getEntityManager() {
		return $this->entityManager;
	}

	/**
	 * @param EntityManager $entityManager
	 */
	public function setEntityManager(EntityManager $entityManager) {
		$this->entityManager = $entityManager;
	}

	/**
	 * @return the $acl
	 */
	public function getAclService() {
		return $this->aclService;
	}

	/**
	 * @param Acl $acl
	 */
	public function setAclService(Acl $aclService) {
		$this->aclService = $aclService;
	}

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

	public function login ($email, $password)
    {
        $hostTable = new \Zend\Db\TableGateway\TableGateway('user', $this->adapter);
        $results = $hostTable->select(array(
            'email' => $email
        ));
        
        $hashedPassword = '';
        foreach ($results as $result)
            $hashedPassword = $result["password"];
        
        $password = $this->hashService->hash_password(
            $password, 
            $this->hashService->find_salt($hashedPassword)
        );
        
        $this->authService->getAdapter()->setIdentity($email);
        $this->authService->getAdapter()->setCredential($password);
        
        if($this->authService->authenticate()){
            $user = $this->getEntityManager()->find('User\Entity\User', array('email' => $email, 'password' => $password));
            $this->setUser($user);
            return true;
        }
        
        return false;
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

    private final function hasRole ($role, $language = NULL, $subject = NULL)
    {
        // is our user authentificated?
        if(!$this->loggedIn() || $this->getUser() === NULL){
            // only return true, if we requested the guest role
            return $role === 'guest';
        }
        
    }

    public final function isAllowed($role, $resource = NULL, $privilege = NULL, $language = null, $subject = null){
        return $this->hasRole($role, $language, $subject) && $this->aclService->isAllowed($role, $resource, $privilege);
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
    
    
    /**
     * Prepares the roles
     * 
     * @see https://github.com/serlo-org/v2.serlo.org/wiki/Roles
     * @param RoleInterface $role
     */
    public function prepareRoles(RoleInterface $role){
        $acl = $this->getAclService();
        $acl->addRole(new $role('guest'));
        $acl->addRole(new $role('login'), 'guest');
        $acl->addRole(new $role('helper'), 'login');
        $acl->addRole(new $role('moderator'), 'helper');
        $acl->addRole(new $role('admin'), 'moderator');
        $acl->addRole(new $role('sysadmin'), 'admin');
    }
}
?>