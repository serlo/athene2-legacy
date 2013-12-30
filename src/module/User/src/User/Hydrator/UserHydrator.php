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
namespace User\Hydrator;

use Zend\Stdlib\Hydrator\HydratorInterface;
use User\Entity\UserInterface;
use Zend\Stdlib\ArrayUtils;

class UserHydrator implements HydratorInterface
{
    use\Uuid\Manager\UuidManagerAwareTrait;

    public function extract($object)
    {
        $object = $this->isValid($object);
        
        return [
            'id' => $object->getId(),
            'username' => $object->getUsername(),
            'password' => $object->getPassword()
        ];
    }

    public function hydrate(array $data, $object)
    {
        $object = $this->isValid($object);
    	$data = ArrayUtils::merge($this->extract($object), $data);
    	
    	$this->getUuidManager()->injectUuid($object, $object->getUuidEntity());
    	$object->setUsername($data['username']);
    	$object->setPassword($data['password']);
    	
    	return $object;
    }

    /**
     *
     * @param UserInterface $object            
     * @return UserInterface
     */
    protected function isValid(UserInterface $object)
    {
        return $object;
    }
}