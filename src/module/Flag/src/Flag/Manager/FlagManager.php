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
namespace Flag\Manager;

use Flag\Exception;
use Doctrine\Common\Collections\ArrayCollection;
use Flag\Options\ModuleOptions;

class FlagManager implements FlagManagerInterface
{
    use \Common\Traits\ObjectManagerAwareTrait,\Uuid\Manager\UuidManagerAwareTrait,\ClassResolver\ClassResolverAwareTrait,\Type\TypeManagerAwareTrait;

    /**
     *
     * @var ModuleOptions
     */
    protected $moduleOptions;

    /**
     *
     * @return ModuleOptions $moduleOptions
     */
    public function getModuleOptions()
    {
        return $this->moduleOptions;
    }

    /**
     *
     * @param ModuleOptions $moduleOptions            
     * @return self
     */
    public function setModuleOptions(ModuleOptions $moduleOptions)
    {
        $this->moduleOptions = $moduleOptions;
        return $this;
    }

    public function getFlag($id)
    {
        $className = $this->getClassResolver()->resolveClassName('Flag\Entity\FlagInterface');
        $flag = $this->getObjectManager()->find($className, $id);
        
        if (! is_object($flag)) {
            throw new Exception\FlagNotFoundException(sprintf('Flag not found by id %d', $id));
        }
        return $flag;
    }

    public function findAllTypes()
    {
        $collection = new ArrayCollection();
        foreach ($this->getModuleOptions()->getTypes() as $type) {
            $collection->add($this->getTypeManager()
                ->findTypeByName($type));
        }
        return $collection;
    }

    public function getType($id)
    {
        return $this->getTypeManager()->getType($id);
    }

    public function findAllFlags()
    {
        $className = $this->getClassResolver()->resolveClassName('Flag\Entity\FlagInterface');
        $flags = $this->getObjectManager()
            ->getRepository($className)
            ->findAll();
        return new ArrayCollection($flags);
    }

    public function removeFlag($id)
    {
        $flag = $this->getFlag($id);
        $this->getObjectManager()->remove($flag);
        return $this;
    }

    public function addFlag($typeId, $content, $uuid,\User\Entity\UserInterface $reporter)
    {
        $type = $this->getType($typeId);
        $object = $this->getUuidManager()->getUuid($uuid);
        
        /* @var $flag \Flag\Entity\FlagInterface */
        $flag = $this->getClassResolver()->resolve('Flag\Entity\FlagInterface');
        $flag->setContent($content);
        $flag->setReporter($reporter);
        $flag->setType($type);
        $flag->setObject($object);
        $this->getObjectManager()->persist($flag);
        return $flag;
    }

    public function persist($object)
    {
        $this->getObjectManager()->persist($object);
        return $this;
    }

    public function flush()
    {
        $this->getObjectManager()->flush();
        return $this;
    }
}