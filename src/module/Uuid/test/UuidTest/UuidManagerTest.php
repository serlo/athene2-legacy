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
namespace UuidTest;

use Uuid\Manager\UuidManager;
use ClassResolver\ClassResolver;

class UuidManagerTest extends \PHPUnit_Framework_TestCase
{

    private $uuidManager;

    protected function tearDown ()
    {
        $this->uuidManager = null;
        parent::tearDown();
    }

    public function setUp ()
    {
        $this->uuidManager = new UuidManager();
        
        $classResolverMock = $this->getMock('ClassResolver\ClassResolver');
        $entityManagerMock = $this->getMock('Doctrine\ORM\EntityManager', array(), array(), '', false);
        $serviceLocatorMock = $this->getMock('Zend\ServiceManager\ServiceManager');
        $uuidMock = $this->getMock('Uuid\Entity\Uuid');
        $repositoryMock = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
                     ->disableOriginalConstructor()
                     ->getMock();
        
        $classResolverMock->expects($this->any())
            ->method('resolveClassName')
            ->will($this->returnValue('Uuid\Entity\Uuid'));
        $uuidMock->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(2));
        $uuidMock->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('foobar'));
        $serviceLocatorMock->expects($this->any())
            ->method('get')
            ->will($this->returnValue($uuidMock));
        $repositoryMock->expects($this->any())
            ->method('findOneBy')
            ->will($this->returnValue($uuidMock));
        $entityManagerMock->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue($repositoryMock));
        
        $this->uuidManager->setServiceLocator($serviceLocatorMock)
            ->setObjectManager($entityManagerMock)
            ->setClassResolver($classResolverMock);
    }

    public function testCreateUuid ()
    {
        $uuid = $this->uuidManager->createUuid();
        $this->assertNotNull($uuid);
    }

    public function testGetUuid ()
    {
        $uuid = $this->uuidManager->createUuid();
        $this->assertEquals($uuid, $this->uuidManager->getUuid(2));
    }

    public function testFindUuidByName ()
    {
        $this->assertEquals(2, $this->uuidManager->findUuidByName('foobar')->getId());
    }
}