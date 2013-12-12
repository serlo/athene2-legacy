<?php
namespace AtheneTest\TestCase;

use ClassResolver\ClassResolver;

abstract class ManagerTest extends \PHPUnit_Framework_TestCase
{

    protected $manager;

    protected $classResolver, $objectManager, $serviceLocator;

    public final function getManager()
    {
        return $this->manager;
    }

    public final function setManager($manager)
    {
        $this->manager = $manager;
        return $this;
    }

    protected final function mockEntity($className, $id)
    {
        $entity = $this->getMock($className);
        $entity->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(1));
        return $entity;
    }

    protected final function prepareClassResolver(array $config)
    {
        if ($this->classResolver) {
            return $this->classResolver;
        }
        
        $this->classResolver = new ClassResolver($config);
        $serviceLocator = $this->prepareServiceLocator(false);
        
        $this->getManager()->setClassResolver($this->classResolver);
        $this->classResolver->setServiceLocator($serviceLocator);
        
        return $this->classResolver;
    }

    protected final function prepareServiceLocator($inject = true)
    {
        if (! $this->serviceLocator) {
            $this->serviceLocator = $this->getMock('Zend\ServiceManager\ServiceManager');
        }
        
        if ($inject) {
            $this->getManager()->setServiceLocator($this->serviceLocator);
        }
        
        return $this->serviceLocator;
    }

    protected final function prepareObjectManager()
    {
        if ($this->objectManager) {
            return $this->objectManager;
        }
        $this->objectManager = $this->getMock('Doctrine\ORM\EntityManager', array(), array(), '', false);
        $this->getManager()->setObjectManager($this->objectManager);
        return $this->objectManager;
    }

    protected final function prepareEntityRepository()
    {
        return $this->getMockBuilder('Doctrine\ORM\EntityRepository')
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected final function prepareFind($repositoryName, $key, $return)
    {
        $objectManager = $this->prepareObjectManager();
        
        $objectManager->expects($this->once())
            ->method('find')
            ->with($repositoryName, $key)
            ->will($this->returnValue($return));
    }

    protected final function prepareFindBy($repositoryName, array $criteria, $return)
    {
        $objectManager = $this->prepareObjectManager();
        $repository = $this->prepareEntityRepository();
        
        $objectManager->expects($this->once())
            ->method('getRepository')
            ->with($repositoryName)
            ->will($this->returnValue($repository));
        
        $repository->expects($this->once())
            ->method('findBy')
            ->with($criteria)
            ->will($this->returnValue($return));
    }

    protected final function prepareFindOneBy($repositoryName, array $criteria, $return)
    {
        $objectManager = $this->prepareObjectManager();
        $repository = $this->prepareEntityRepository();
        
        $objectManager->expects($this->once())
            ->method('getRepository')
            ->with($repositoryName)
            ->will($this->returnValue($repository));
        
        $repository->expects($this->once())
            ->method('findOneBy')
            ->with($criteria)
            ->will($this->returnValue($return));
    }
}