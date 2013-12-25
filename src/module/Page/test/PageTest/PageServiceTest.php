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
namespace PageTest;

use Page\Service\PageService;
use VersioningTest\Entity\RevisionFake;
use VersioningTest\Entity\RepositoryFake;

class PageServiceTest extends \PHPUnit_Framework_TestCase
{

    protected $uuidMock,$roleMock,$normalizedMock,$pageManagerMock,$userServiceMock, $objectRepositoryMock, $objectManagerMock, $repositoryFake, $repositoryServiceMock, $revisionFake, $pageService, $repositoryMock, $pageRepositoryMock, $pageRevisionMock, $repositoryManagerMock;

    public function setUp()
    {
        parent::setUp();
        
        $this->uuidMock = $this->getMock('Uuid\Entity\Uuid');
        $this->normalizedMock = $this->getMock('Common\Normalize\Normalized');
        $this->pageManagerMock = $this->getMock('Page\Manager\PageManager');
        $this->userServiceMock = $this->getMock('User\Service\UserService');
        $this->objectRepositoryMock = $this->getMock('Doctrine\Common\Persistence\ObjectRepository');
        $this->objectManagerMock = $this->getMock('Doctrine\ORM\EntityManager', array(), array(), '', false);
        $this->roleMock = $this->getMock('User\Entity\Role');
        $this->repositoryServiceMock = $this->getMock('Versioning\Service\RepositoryService');
        $this->pageRepositoryMock = $this->getMock('Page\Entity\PageRepository');
        $this->pageRevisionMock = $this->getMock('Page\Entity\PageRevision');
        $this->repositoryManagerMock = $this->getMock('Versioning\RepositoryManager');
        $this->pageService = new PageService();
        $this->pageService->setEntity($this->pageRepositoryMock);
        $this->repositoryMock = $this->getMock('Versioning\Entity\Repository');
        $this->revisionFake = new RevisionFake();
        $this->revisionFake->setId(100);
        $this->repositoryFake = new RepositoryFake();
        $this->repositoryFake->addRevision($this->revisionFake);
        $this->repositoryFake->setCurrentRevision($this->revisionFake);
        $this->repositoryServiceMock->addRevision($this->revisionFake);
        $this->pageService->setRepositoryManager($this->repositoryManagerMock);
        $this->pageService->setObjectManager($this->objectManagerMock);
        $this->pageService->setManager($this->pageManagerMock);
    }

    public function testGetCurrentRevision()
    {
        $this->getRepository();
        $this->repositoryServiceMock->expects($this->once())
            ->method('getCurrentRevision')
            ->will($this->returnValue($this->pageRevisionMock));
        $this->assertInstanceOf('Page\Entity\PageRevisionInterface', $this->pageService->getCurrentRevision());
    }

    public function testHasCurrentRevision()
    {
        $this->getRepository();
        $this->repositoryServiceMock->expects($this->once())
            ->method('hasCurrentRevision')
            ->will($this->returnValue(true));
        $this->assertTrue($this->pageService->hasCurrentRevision());
    }

    public function testSetCurrentRevision()
    {
        $this->getRepository();
        $this->repositoryServiceMock->expects($this->once())
            ->method('checkOutRevision')
            ->with($this->revisionFake->getId());
        $this->assertSame($this->pageService, $this->pageService->setCurrentRevision($this->revisionFake));
    }

    public function testSetRole()
    {
        $this->pageRepositoryMock->expects($this->once())
            ->method('setRole');
        $this->assertNull($this->pageService->setRole($this->roleMock));
    }

    public function testGetRoleById()
    {
        $this->pageService->getObjectManager()
            ->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($this->objectRepositoryMock));
        $this->objectRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->will($this->returnValue($this->roleMock));
        $this->assertEquals($this->roleMock, $this->pageService->getRoleById(100));
    }

    public function testCountRoles()
    {
        $this->pageService->getObjectManager()
            ->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($this->objectRepositoryMock));
        $this->objectRepositoryMock->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue(array(
            $this->roleMock
        )));
        $this->assertEquals(1, $this->pageService->countRoles());
    }

    public function testHasRole()
    {
        $this->pageRepositoryMock->expects($this->once())
            ->method('hasRole')
            ->will($this->returnValue(true));
        $this->assertTrue($this->pageService->hasRole($this->roleMock));
    }

    private function getRepository()
    {
        $this->repositoryManagerMock->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($this->repositoryServiceMock));
    }
    
    public function testTrashRevision(){
        $this->pageManagerMock->expects($this->once())->method('getRevision')->will($this->returnValue($this->pageRevisionMock));
        $this->pageRevisionMock->expects($this->once())->method('trash');
        $this->assertEquals($this->pageService,$this->pageService->trashRevision(100));
    }
    
        public function testDeleteRevision(){
        $this->getRepository();
        $this->pageManagerMock->expects($this->once())->method('getRevision')->will($this->returnValue($this->pageRevisionMock));
        $this->assertEquals($this->pageService,$this->pageService->deleteRevision(100));
    
}

    public function testGetRepositoryId(){
        $this->pageRepositoryMock->expects($this->once())->method('getId')->will($this->returnValue(100));
        $this->assertEquals(100,$this->pageService->getRepositoryId());
        
    }

}