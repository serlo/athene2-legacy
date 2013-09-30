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
namespace LinkTest;

use Link\Manager\SharedLinkManager;
use LinkTest\Fake\LinkTypeFake;

class SharedLinkManagerTest extends \PHPUnit_Framework_TestCase
{

    protected $sharedLinkManager, $objectManagerMock, $classResolverMock, $serviceLocatorMock, $linkManagerMock;

    public function setUp()
    {
        parent::setUp();
        
        $this->sharedLinkManager = new SharedLinkManager();
        
        $this->objectManagerMock = $this->getMock('Doctrine\ORM\EntityManager', array(), array(), '', false);
        $this->classResolverMock = $this->getMock('ClassResolver\ClassResolver');
        $this->serviceLocatorMock = $this->getMock('Zend\ServiceManager\ServiceManager');
        $this->linkManagerMock = $this->getMock('Link\Manager\LinkManager');
        
        $this->linkManagerMock->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(1));
        
        $this->sharedLinkManager->setObjectManager($this->objectManagerMock);
        $this->sharedLinkManager->setClassResolver($this->classResolverMock);
        $this->sharedLinkManager->setServiceLocator($this->serviceLocatorMock);
    }

    public function testGetLinkManager()
    {
        $linkTypeFake = new LinkTypeFake();
        $linkTypeFake->setId(1);
        
        $this->classResolverMock->expects($this->once())
            ->method('resolveClassName')
            ->will($this->returnValue('Link\Manager\LinkManager'));
        
        $this->serviceLocatorMock->expects($this->once())
            ->method('get')
            ->will($this->returnValue($this->linkManagerMock));
        
        $this->linkManagerMock->expects($this->once())
            ->method('setEntity')
            ->with($linkTypeFake);
        
        $this->objectManagerMock->expects($this->once())
            ->method('find')
            ->with('SomeRepository', 1)
            ->will($this->returnValue($linkTypeFake));
        
        $this->assertEquals($this->linkManagerMock, $this->sharedLinkManager->getLinkManager(1, 'SomeRepository'));
    }

    public function testFindLinkManagerByName()
    {
        $linkTypeFake = new LinkTypeFake();
        $linkTypeFake->setId(1);
        
        $repositoryMock = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
            ->disableOriginalConstructor()
            ->getMock();
        
        $repositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(array(
            'name' => 'foobar'
        ))
            ->will($this->returnValue($linkTypeFake));
        
        $this->classResolverMock->expects($this->once())
            ->method('resolveClassName')
            ->will($this->returnValue('Link\Manager\LinkManager'));
        
        $this->serviceLocatorMock->expects($this->once())
            ->method('get')
            ->will($this->returnValue($this->linkManagerMock));
        
        $this->linkManagerMock->expects($this->once())
            ->method('setEntity')
            ->with($linkTypeFake);
        
        $this->objectManagerMock->expects($this->once())
            ->method('getRepository')
            ->with('SomeRepository')
            ->will($this->returnValue($repositoryMock));
        
        $this->assertEquals($this->linkManagerMock, $this->sharedLinkManager->findLinkManagerByName('foobar', 'SomeRepository'));
    }
}