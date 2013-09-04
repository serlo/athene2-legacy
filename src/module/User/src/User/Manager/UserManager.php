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

class UserManager extends AbstractManager implements UserManagerInterface
{
    use \Common\Traits\ObjectManagerAwareTrait, \Zend\EventManager\EventManagerAwareTrait;

    public function get ($user)
    {
        if ($user instanceof \User\Entity\UserInterface) {
        } elseif ($user instanceof \User\Service\UserServiceInterface){
            return $user;
        } elseif (is_numeric($user) || is_string($user)){
            $user = $this->find($user);
        } else {
            throw new \InvalidArgumentException();
        }

        if (! is_object($user))
            throw new UserNotFoundException(sprintf('User not found'));
        
        if (! $this->has($user) ) {
            $this->createService($user);
        }
        
        return $this->getInstance($user->getId());
    }

    public function create ($data)
    {
        $user = $this->createUserEntity();
        $user->populate($data);
        
        $this->getObjectManager()->persist($user);
        $this->getObjectManager()->flush();
        
        $this->getEventManager()->trigger('create', $this, array('user' => $user));
        
        return $this->createService($user);
    }

    public function createUser ()
    {
        return $this->createService($this->createUserEntity());
    }

    public function createUserEntity ()
    {
        $user = $this->resolveClassName('User\Entity\UserInterface');
        return new $user();
    }

    public function has ($entity)
    {
        if(is_object($entity))
            $entity = $entity->getId();
        
        return $this->hasInstance($entity);
    }
    
    public function findAllUsers(){
        $collection = new ArrayCollection($this->getObjectManager()->getRepository($this->resolveClassName('User\Entity\UserInterface'))->findAll());
        return new UserCollection($collection, $this);
    }
    
    
    public function findAllRoles(){
        return $this->getObjectManager()->getRepository($this->resolveClassName('User\Entity\RoleInterface'))->findAll();
    }
    
    public function findRole($id){
        return $this->getObjectManager()->get($this->resolveClassName('User\Entity\RoleInterface'), $id);        
    }

    protected function find ($id)
    {
        if (is_numeric($id)) {
            $user = $this->getObjectManager()->find($this->resolveClassName('User\Entity\UserInterface'), $id);
        } else {
            $user = $this->getObjectManager()
                ->getRepository($this->resolveClassName('User\Entity\UserInterface'))
                ->findOneBy(array(
                'email' => $id
            ));
            if (! is_object($user)) {
                $user = $this->getObjectManager()
                    ->getRepository($this->resolveClassName('User\Entity\UserInterface'))
                    ->findOneBy(array(
                    'username' => $id
                ));
            }
        }        
        return $user;
    }

    protected function createService (UserInterface $entity)
    {
        $instance = parent::createInstance('User\Service\UserServiceInterface');
        $instance->setEntity($entity);
        $this->addInstance($entity->getId(), $instance);
        return $instance;
    }
}