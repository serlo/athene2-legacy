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

class UserManager extends AbstractManager implements UserManagerInterface
{
    use \Common\Traits\ObjectManagerAwareTrait, \Zend\EventManager\EventManagerAwareTrait;

    public function get ($user)
    {
        if ($user instanceof \User\Entity\UserInterface) {
            $user = $user->getId();
        } elseif ($user instanceof \User\Service\UserServiceInterface){
            $user = $user->getId();            
        } elseif (is_numeric($user) || is_string($user)){
            
        } else {
            throw new \InvalidArgumentException();
        }
        
        if (! $this->has($user) ) {
            return $this->find($user);
        }
        
        return $this->getInstance($user);
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
        if (! is_object($user))
            throw new UserNotFoundException(sprintf('User `%s` not found', $id));
        
        return $this->createService($user);
    }

    protected function createService (UserInterface $entity)
    {
        $instance = parent::createInstance('User\Service\UserServiceInterface');
        $instance->setEntity($entity);
        $this->addInstance($entity->getId(), $instance);
        return $instance;
    }
}