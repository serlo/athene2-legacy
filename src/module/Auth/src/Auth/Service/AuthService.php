<?php
namespace Auth\Service;

use Zend\Permissions\Acl\Acl;
use User\Service\UserServiceInterface;
use Doctrine\ORM\EntityManager;
use Zend\Permissions\Acl\Role\RoleInterface;
use Core\Service\SubjectService;
use Zend\Permissions\Acl\Resource\GenericResource as AclResource;

class AuthService implements AuthServiceInterface
{
    use \User\Manager\UserManagerAwareTrait, \Subject\Manager\SubjectManagerAwareTrait, \Language\Manager\LanguageManagerAwareTrait;

    
    private $authService, $hashService, $adapter, $authResult, $aclService, $userService, $controller;

    private $entityManager;

    private $user;

    /**
     *
     * @return the $controller
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     *
     * @param field_type $controller            
     */
    public function setController($controller)
    {
        $this->controller = $controller;
    }

    /**
     *
     * @param field_type $userService            
     */
    public function setUserService(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    /**
     *
     * @return the $user
     */
    public function getUser()
    {
        if(!$this->user && $this->getAuthService()->hasIdentity()){
            $entity = $this->getEntityManager()->getRepository('User\Entity\User')->findOneByEmail($this->getAuthService()->getIdentity());
            if(!is_object($entity)){
                $this->logout();
                throw new \Exception('Identity not found');
            }
            $this->setUser($entity);
        }
        return $this->user;
    }
    
    public function getIdentity(){
        return $this->getUser();
    }

    /**
     *
     * @param User $user            
     */
    public function setUser(UserServiceInterface $user = NULL)
    {
        $this->user = $user;
    }

    /**
     *
     * @return the $entityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     *
     * @param EntityManager $entityManager            
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     *
     * @return the $acl
     */
    public function getAclService()
    {
        return $this->aclService;
    }

    /**
     *
     * @param Acl $acl            
     */
    public function setAclService(Acl $aclService)
    {
        $this->aclService = $aclService;
    }

    /**
     *
     * @return the $adapter
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     *
     * @param field_type $adapter            
     */
    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;
    }

    public function login($email, $password)
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
            $this->setUser($this->getUserManager()
                ->get($email));
            $this->getUser()->updateLoginData();
        }
        
        return $result;
    }

    public function logout()
    {
        if ($this->authService->hasIdentity())
            $this->authService->clearIdentity();
    }

    public function loggedIn()
    {
        return $this->authService->hasIdentity();
    }

    public function hasRole($role)
    {
        return array_search($role, $this->getRoles()) !== FALSE;
    }

    public function getRoles()
    {
        if($this->loggedIn()){
            return $this->getUserManager()->get($this->getUser())->getRoleNames($this->getLanguageManager()->getLanguageFromRequest());
        } else {
            return array('guest');
        }
    }

    /**
     *
     * @return the $authService
     */
    public function getAuthService()
    {
        return $this->authService;
    }

    /**
     *
     * @return the $hashService
     */
    public function getHashService()
    {
        return $this->hashService;
    }

    /**
     *
     * @param field_type $authService            
     */
    public function setAuthService(\Zend\Authentication\AuthenticationService $authService)
    {
        $this->authService = $authService;
    }

    /**
     *
     * @param field_type $hashService            
     */
    public function setHashService(HashServiceInterface $hashService)
    {
        $this->hashService = $hashService;
    }

    /**
     * Prepares the roles
     *
     * @see https://github.com/serlo-org/v2.serlo.org/wiki/Roles
     * @param RoleInterface $role            
     */
    public function prepareRoles($role)
    {
        $acl = $this->getAclService();
        $acl->addRole(new $role('guest'));
        $acl->addRole(new $role('login'), 'guest');
        $acl->addRole(new $role('helper'), 'login');
        $acl->addRole(new $role('moderator'), 'helper');
        $acl->addRole(new $role('admin'), 'moderator');
        $acl->addRole(new $role('sysadmin'), 'admin');
    }

    public function addPermissions(array $config)
    {
        $acl = $this->getAclService();
        
        foreach ($config as $resource => $permissions) {
            if (! $acl->hasResource($resource)) {
                $resource = $this->_createResource($resource);
                $acl->addResource($resource);
            }
            foreach ($permissions as $role => $value) {
                if (is_array($value)) {
                    foreach ($value as $privilege => $rule) {
                        $this->_iterPermission($role, $resource, $rule, $privilege);
                    }
                } else {
                    $this->_iterPermission($role, $resource, $value);
                }
            }
        }
    }

    public function hasResource($resource)
    {
        $acl = $this->getAclService();
        return $acl->hasResource($this->_resource($resource));
    }

    public function hasAccess($resource, $permission = NULL)
    {
        $resource = $this->_resource($resource);
        if (! $this->hasResource($resource)) {
            return true;
        } else {
            return $this->_isAllowed($resource, $permission);
        }
    }

    public function _isAllowed($resource = NULL, $privilege = NULL)
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

    private function _createResource($resource)
    {
        return new AclResource($this->_resource($resource));
    }

    private function _resource($resource)
    {
        return strtolower($resource);
    }

    private function _iterPermission($role, $resource, $rule, $privilege = NULL)
    {
        $allowedRules = array(
            'allow',
            'deny'
        );
        $acl = $this->getAclService();
        
        if (! in_array($rule, $allowedRules))
            throw new \Exception('Unallowed method `' . $rule . '`. Use `allow` or `deny` only.');
        
        $acl->$rule($role, $resource, $privilege);
    }
}
?>