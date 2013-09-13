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
namespace Link\Manager;

use Link\Entity\LinkTypeInterface;

class SharedLinkManager extends AbstractManager implements SharedLinkManagerInterface
{
    use \Common\Traits\ObjectManagerAwareTrait;
    
    public function get($type, $typeClassName){
        
        if(!$this->containsKey($type)){
            $typeEntity = $this->getObjectManager()->getRepository($typeClassName)->findOneByName($type);
            if(!$typeEntity instanceof $typeClassName){
                throw new \Link\Exception\TypeNotFoundException();
            }
            $this->createService($typeEntity);
        }
        
        return $this->getInstance($type);
    }
    
    public function add(LinkManagerInterface $manager){
        $this->addInstance($manager->getEntity()->getName(), $manager);
        return $this;
    }
    
    public function containsKey($key){
        return $this->hasInstance($key);
    }
    
    public function has(LinkTypeInterface $type){
        return $this->containsKey($type->getName());
    }
    
    protected function createService(LinkTypeInterface $type){
        $instance = $this->createInstance('Link\Manager\LinkManagerInterface');
        $instance->setEntity($type);
        $this->add($instance);
        return $instance;
    }
}