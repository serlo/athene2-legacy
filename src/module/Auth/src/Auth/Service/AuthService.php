<?php
namespace Auth\Service;

use Zend\Permissions\Acl\Acl;
use User\Service\UserServiceInterface;
use Doctrine\ORM\EntityManager;
use Zend\Permissions\Acl\Role\RoleInterface;
use Core\Service\LanguageService;
use Core\Service\SubjectService;

class AuthService implements AuthServiceInterface
{

    private $authService, $hashService, $adapter, $authResult, $aclService, $userService, $languageService, $subjectService;

    private $entityManager;

    private $user;

    /**
     *
     * @return the $languageService
     */
    public function getLanguageService ()
    {
        return $this->languageService;
    }

    /**
     *
     * @return the $subjectService
     */
    public function getSubjectService ()
    {
        return $this->subjectService;
    }

    /**
     *
     * @param LanguageService $languageService            
     */
    public function setLanguageService (LanguageService $languageService)
    {
        $this->languageService = $languageService;
    }

    /**
     *
     * @param SubjectService $subjectService            
     */
    public function setSubjectService (SubjectService $subjectService)
    {
        $this->subjectService = $subjectService;
    }

    /**
     *
     * @return the $userService
     */
    public function getUserService ()
    {
        return $this->userService;
    }

    /**
     *
     * @param field_type $userService            
     */
    public function setUserService (UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    /**
     *
     * @return the $user
     */
    public function getUser ()
    {
        return $this->user;
    }

    /**
     *
     * @param User $user            
     */
    public function setUser ($user = NULL)
    {
        $this->user = $user;
    }

    /**
     *
     * @return the $entityManager
     */
    public function getEntityManager ()
    {
        return $this->entityManager;
    }

    /**
     *
     * @param EntityManager $entityManager            
     */
    public function setEntityManager (EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     *
     * @return the $acl
     */
    public function getAclService ()
    {
        return $this->aclService;
    }

    /**
     *
     * @param Acl $acl            
     */
    public function setAclService (Acl $aclService)
    {
        $this->aclService = $aclService;
    }

    /**
     *
     * @return the $adapter
     */
    public function getAdapter ()
    {
        return $this->adapter;
    }

    /**
     *
     * @param field_type $adapter            
     */
    public function setAdapter ($adapter)
    {
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
        
        $password = $this->hashService->hash_password($password, $this->hashService->find_salt($hashedPassword));
        
        $this->authService->getAdapter()->setIdentity($email);
        $this->authService->getAdapter()->setCredential($password);
        
        $result = $this->authService->authenticate();
        
        if ($result->isValid()) {
            $this->setUser($this->getUserService()->get($email));
        }
        
        return $result;
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
        return array_search($role, $this->getRoles()) !== FALSE;
    }

    public function getRoles ()
    {
        return array_merge(array(
            'guest'
        ), $this->getUserService()->getRoles($this->getUser(), $this->getLanguageService()
            ->get(), $this->getSubjectService()
            ->get()));
    }

    public function isAllowed ($role, $resource = NULL, $privilege = NULL)
    {
        $return = false;
        $roles = $this->getRoles();
        
        foreach ($roles as $role) {
            $return = $this->getAclService()->isAllowed($role, $resource, $privilege);
            if ($return)
                return $return;
        }
        return false;
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
    public function prepareRoles ($role)
    {
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