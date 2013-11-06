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

use User\Entity\UserInterface;
use User\Exception\UserNotFoundException;
use User\Collection\UserCollection;
use Doctrine\Common\Collections\ArrayCollection;
use User\Exception\InvalidArgumentException;

class UserManager implements UserManagerInterface
{
    use \Uuid\Manager\UuidManagerAwareTrait,\Common\Traits\ObjectManagerAwareTrait,\Common\Traits\InstanceManagerTrait,\Common\Traits\AuthenticationServiceAwareTrait;

    public function getUser($id)
    {
        if (! is_numeric($id))
            throw new InvalidArgumentException(sprintf('Expected numeric but got %s', gettype($id)));
        
        if (! $this->hasInstance($id)) {
            $user = $this->getObjectManager()->find($this->getClassResolver()
                ->resolveClassName('User\Entity\UserInterface'), $id);
            if (! $user)
                throw new UserNotFoundException(sprintf('User %s not found', $id));
            
            $instance = $this->createService($user);
            $this->addInstance($user->getId(), $instance);
        }
        
        return $this->getInstance($id);
    }

    public function getUserFromAuthenticator()
    {
        if ($this->getAuthenticationService()->hasIdentity()) {
            $email = $this->getAuthenticationService()->getIdentity();
            try {
                $user = $this->findUserByEmail($email);
                if ($user->isTrashed() || ! $user->hasRole('login')) {
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
        if (! $user)
            throw new UserNotFoundException(sprintf('User %s not found', $username));
        return $this->getUser($user->getId());
    }

    public function findUserByUsername($username)
    {
        $user = $this->getUserEntityRepository()->findOneBy(array(
            'username' => $username
        ));
        if (! $user)
            throw new UserNotFoundException(sprintf('User %s not found', $username));
        return $this->getUser($user->getId());
    }

    public function findUserByEmail($email)
    {
        $user = $this->getUserEntityRepository()->findOneBy(array(
            'email' => $email
        ));
        if (! $user)
            throw new UserNotFoundException(sprintf('User with email %s not found', $email));
        return $this->getUser($user->getId());
    }

    public function createUser(array $data)
    {
        $user = $this->getClassResolver()->resolve('User\Entity\UserInterface');
        $this->getUuidManager()->injectUuid($user);
        $user->populate($data);
        $this->getObjectManager()->persist($user);
        $instance = $this->createService($user);
        $this->addInstance($user->getId(), $instance);
        return $instance;
    }

    public function purgeUser($id)
    {
        $user = $this->getUser($id);
        $this->removeInstance($user->getId());
        $this->getObjectManager()->remove($user->getEntity());
        unset($user);
        return $this;
    }

    public function trashUser($id)
    {
        $user = $this->getUser($id);
        $user->setTrashed(true);
        $this->getObjectManager()->persist($user->getEntity());
        return $this;
    }

    public function findAllUsers()
    {
        $collection = new ArrayCollection($this->getObjectManager()
            ->getRepository($this->getClassResolver()
            ->resolveClassName('User\Entity\UserInterface'))
            ->findAll());
        return new UserCollection($collection, $this);
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
        return $this->getObjectManager()->find($this->getClassResolver()
            ->resolveClassName('User\Entity\RoleInterface'), $id);
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
    
    protected function getUserEntityRepository()
    {
        return $this->getObjectManager()->getRepository($this->getClassResolver()
            ->resolveClassName('User\Entity\UserInterface'));
    }

    protected function createService(UserInterface $entity)
    {
        /* @var $instance \User\Service\UserServiceInterface */
        $instance = $this->createInstance('User\Service\UserServiceInterface');
        $instance->setEntity($entity);
        $instance->setManager($this);
        return $instance;
    }
}