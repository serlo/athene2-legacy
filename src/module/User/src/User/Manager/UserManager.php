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
namespace User\Manager;

use User\Exception\UserNotFoundException;
use User\Collection\UserCollection;
use Doctrine\Common\Collections\ArrayCollection;
use User\Exception;

class UserManager implements UserManagerInterface
{
    use\Uuid\Manager\UuidManagerAwareTrait,\Common\Traits\ObjectManagerAwareTrait,\Common\Traits\InstanceManagerTrait,\Common\Traits\AuthenticationServiceAwareTrait;

    public function getUser($id)
    {
        $user = $this->getObjectManager()->find($this->getClassResolver()
            ->resolveClassName('User\Entity\UserInterface'), $id);
        if (! $user) {
            throw new UserNotFoundException(sprintf('User %s not found', $id));
        }
        
        return $user;
    }

    public function getUserFromAuthenticator()
    {
        if ($this->getAuthenticationService()->hasIdentity()) {
            $user = $this->getAuthenticationService()->getIdentity();
            try {
                $user = $this->getUser($user->getId());
                $role = $this->findRoleByName('login');
                if ($user->getTrashed() || ! $user->hasRole($role)) {
                    $this->getAuthenticationService()->clearIdentity();
                } else {
                    return $user;
                }
            } catch (UserNotFoundException $e) {
                $this->getAuthenticationService()->clearIdentity();
            }
        }
        return null;
    }

    public function findUserByToken($username)
    {
        $user = $this->getUserEntityRepository()->findOneBy(array(
            'token' => $username
        ));
        
        if (! $user) {
            throw new UserNotFoundException(sprintf('User %s not found', $username));
        }
        
        return $user;
    }

    public function findUserByUsername($username)
    {
        $user = $this->getUserEntityRepository()->findOneBy(array(
            'username' => $username
        ));
        
        if (! $user) {
            throw new UserNotFoundException(sprintf('User %s not found', $username));
        }
        
        return $user;
    }

    public function findUserByEmail($email)
    {
        $user = $this->getUserEntityRepository()->findOneBy(array(
            'email' => $email
        ));
        
        if (! $user) {
            throw new UserNotFoundException(sprintf('User with email %s not found', $email));
        }
        
        return $user;
    }

    public function createUser(array $data)
    {
        $user = $this->getClassResolver()->resolve('User\Entity\UserInterface');
        $this->getUuidManager()->injectUuid($user);
        $user->populate($data);
        $this->getObjectManager()->persist($user);
        return $user;
    }

    public function purgeUser($id)
    {
        $user = $this->getUser($id);
        $this->removeInstance($user->getId());
        $this->getObjectManager()->remove($user->getEntity());
        unset($user);
        return $this;
    }

    public function findAllUsers()
    {
        return new ArrayCollection($this->getObjectManager()
            ->getRepository($this->getClassResolver()
            ->resolveClassName('User\Entity\UserInterface'))
            ->findAll());
    }

    public function findAllRoles()
    {
        return $this->getObjectManager()
            ->getRepository($this->getClassResolver()
            ->resolveClassName('User\Entity\RoleInterface'))
            ->findAll();
    }

    public function findRole($id)
    {
        $role = $this->getObjectManager()->find($this->getClassResolver()
            ->resolveClassName('User\Entity\RoleInterface'), $id);
        if (! is_object($role))
            throw new Exception\RuntimeException(sprintf('Role not found by id %u', $id));
        return $role;
    }

    public function findRoleByName($role)
    {
        return $this->getObjectManager()
            ->getRepository($this->getClassResolver()
            ->resolveClassName('User\Entity\RoleInterface'))
            ->findOneBy(array(
            'name' => $role
        ));
    }

    public function getUnassociatedRoles($id)
    {
        $userRoles = $this->getUser($id)->getRoles();
        $allRoles = $this->findAllRoles();
        return array_diff($allRoles, $userRoles);
    }

    protected function getUserEntityRepository()
    {
        return $this->getObjectManager()->getRepository($this->getClassResolver()
            ->resolveClassName('User\Entity\UserInterface'));
    }
}