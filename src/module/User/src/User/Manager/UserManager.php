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
use User\Service\UserServiceInterface;

class UserManager implements UserManagerInterface
{
    use \Common\Traits\ObjectManagerAwareTrait,\Zend\EventManager\EventManagerAwareTrait,\Common\Traits\InstanceManagerTrait,\Common\Traits\AuthenticationServiceAwareTrait;

    public function addUser(UserServiceInterface $user){
        $this->addInstance($user->getId(), $user);
        return $this;
    }
    
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
            return $instance;
        }
        
        return $this->getInstance($id);
    }
    
    public function getUserFromAuthenticator(){
        if($this->getAuthenticationService()->hasIdentity()){
            $email = $this->getAuthenticationService()->getIdentity();
            try{
                $user = $this->findUserByEmail($email);
                if($user->getRemoved() || !$user->hasRole('login')){
                    $this->getAuthenticationService()->clearIdentity();
                } else {
                    return $user;
                }
            } catch(UserNotFoundException $e){
                $this->getAuthenticationService()->clearIdentity();
            }
        }
        return null;
    }

    public function findUserByUsername($username)
    {
        $user = $this->getObjectManager()
            ->getRepository($this->getClassResolver()
            ->resolveClassName('User\Entity\UserInterface'))
            ->findOneBy(array(
            'username' => $username
        ));
        if (! $user)
            throw new UserNotFoundException(sprintf('User %s not found', $username));
        return $this->getUser($user->getId());
    }

    public function findUserByEmail($email)
    {
        $user = $this->getObjectManager()
            ->getRepository($this->getClassResolver()
            ->resolveClassName('User\Entity\UserInterface'))
            ->findOneBy(array(
            'email' => $email
        ));
        if (! $user)
            throw new UserNotFoundException(sprintf('User with email %s not found', $email));
        return $this->getUser($user->getId());
    }

    public function createUser(array $data)
    {
        $user = $this->createUserEntity();
        $user->populate($data);
        $this->getObjectManager()->persist($user);
        $this->getEventManager()->trigger('create', $this, array(
            'user' => $user
        ));
        return $user;//$this->createService($user);
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
        $user->setRemoved(true);
        $this->getObjectManager()->persist($user->getEntity());
        return $this;
    }

    public function createUserEntity()
    {
        $user = $this->getClassResolver()->resolveClassName('User\Entity\UserInterface');
        return new $user();
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

    protected function createService(UserInterface $entity)
    {
        /* @var $instance \User\Service\UserServiceInterface */
        $instance = $this->createInstance('User\Service\UserServiceInterface');
        $instance->setEntity($entity);
        $instance->setManager($this);
        return $instance;
    }
}