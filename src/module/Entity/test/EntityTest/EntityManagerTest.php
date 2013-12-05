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
namespace EntityTest;

use Entity\Manager\EntityManager;

class EntityManagerTest extends \PHPUnit_Framework_TestCase
{

    protected $entityManager, $objectManagerMock, $uuidManagerMock, $classResolverMock, $serviceLocatorMock, $pluginManagerMock, $languageManagerMock, $entityMock, $entityServiceMock, $repositoryMock;

    public function setUp()
    {
        parent::setUp();
        $this->entityManager = new EntityManager();
        
        $this->entityManager->setConfig(array(
            
            'plugins' => array(
                'factories' => array()
            ),
            'listeners' => array(),
            'types' => array(
                'foobar' => array(
                    'plugins' => array()
                )
            )
        ));
        
        $this->objectManagerMock = $this->getMock('Doctrine\ORM\EntityManager', array(), array(), '', false);
        $this->uuidManagerMock = $this->getMock('Uuid\Manager\UuidManager');
        $this->classResolverMock = $this->getMock('ClassResolver\ClassResolver');
        $this->serviceLocatorMock = $this->getMock('Zend\ServiceManager\ServiceManager');
        $this->pluginManagerMock = $this->getMock('Entity\Plugin\PluginManager');
        $this->entityMock = $this->getMock('Entity\Entity\Entity');
        $this->entityServiceMock = $this->getMock('Entity\Service\EntityService');
        $this->repositoryMock = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
            ->disableOriginalConstructor()
            ->getMock();
        
        $this->objectManagerMock->expects($this->never())
            ->method('flush');
        
        $this->entityManager->setObjectManager($this->objectManagerMock);
        $this->entityManager->setUuidManager($this->uuidManagerMock);
        $this->entityManager->setClassResolver($this->classResolverMock);
        $this->entityManager->setServiceLocator($this->serviceLocatorMock);
        $this->entityManager->setPluginManager($this->pluginManagerMock);
    }

    private function createService()
    {
        $this->classResolverMock->expects($this->atLeastOnce())
            ->method('resolveClassName')
            ->will($this->returnValueMap(array(
            array(
                'Entity\Entity\TypeInterface',
                'Entity\Entity\Type'
            ),
            array(
                'Entity\Entity\EntityInterface',
                'Entity\Entity\Entity'
            ),
            array(
                'Entity\Service\EntityServiceInterface',
                'Entity\Service\EntityService'
            )
        )));
        
        $this->serviceLocatorMock->expects($this->once())
            ->method('get')
            ->will($this->returnValue($this->entityServiceMock));
        
        $this->entityServiceMock->expects($this->once())
            ->method('setEntity')
            ->with($this->entityMock);
        
        $typeMock = $this->getMock('Entity\Entity\Type');
        $typeMock->expects($this->atLeastOnce())
            ->method('getName')
            ->will($this->returnValue('foobar'));
        
        $this->entityMock->expects($this->atLeastOnce())
            ->method('getType')
            ->will($this->returnValue($typeMock));
    }

    public function testGetEntity()
    {
        $this->createService();
        $this->entityMock->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(1));
        
        $this->objectManagerMock->expects($this->once())
            ->method('find')
            ->will($this->returnValue($this->entityMock));
        
        $this->assertSame($this->entityServiceMock, $this->entityManager->getEntity(1));
    }

    /**
     * @expectedException \Entity\Exception\InvalidArgumentException
     */
    public function testGetEntityException()
    {
        $this->entityManager->getEntity('asdf');
    }

    public function testCreateEntity()
    {
        $this->createService();
        
        $this->entityMock->expects($this->never())
            ->method('getId')
            ->will($this->returnValue(1));
        
        $this->objectManagerMock->expects($this->once())
            ->method('persist');
        
        $this->objectManagerMock->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($this->repositoryMock));
        
        $this->repositoryMock->expects($this->once())
            ->method('findOneBy')
            ->will($this->returnValue($this->getMock('Entity\Entity\Type')));
        
        $this->classResolverMock->expects($this->once())
            ->method('resolve')
            ->will($this->returnValue($this->entityMock));
        
        $languageServiceMock = $this->getMock('Language\Service\LanguageService');
        $languageServiceMock->expects($this->atLeastOnce())
            ->method('getEntity')
            ->will($this->returnValue($this->getMock('Language\Entity\LanguageEntityInterface')));
        
        $this->assertSame($this->entityServiceMock, $this->entityManager->createEntity('foobar', array(
            'asdf'
        ), $languageServiceMock));
    }
}