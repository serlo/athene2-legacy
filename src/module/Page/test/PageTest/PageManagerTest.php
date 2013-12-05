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

use Page\Manager\PageManager;

class PageManagerTest extends \PHPUnit_Framework_TestCase
{

    protected $pageManager,$pageManagerMock, $objectManagerMock, $uuidManagerMock, $classResolverMock, $serviceLocatorMock, $repositoryManagerMock, $languageManagerMock, $pageRepositoryMock, $pageRevisionMock, $pageServiceMock, $repositoryMock, $userMock,$repositoryServiceMock,$languageMock;

    public function setUp()
    {
        parent::setUp();
        $this->pageManager = new PageManager();
        $this->languageMock = $this->getMock('Language\Entity\LanguageEntity');
        $this->pageManagerMock = $this->getMock('Page\Manager\PageManager');
        $this->userMock = $this->getMock('User\Entity\User');
        $this->objectManagerMock = $this->getMock('Doctrine\ORM\EntityManager', array(), array(), '', false);
        $this->uuidManagerMock = $this->getMock('Uuid\Manager\UuidManager');
        $this->classResolverMock = $this->getMock('ClassResolver\ClassResolver');
        $this->serviceLocatorMock = $this->getMock('Zend\ServiceManager\ServiceManager');
        $this->pageRepositoryMock = $this->getMock('Page\Entity\PageRepository');
        $this->pageRevisionMock = $this->getMock('Page\Entity\PageRevision');
        $this->pageServiceMock = $this->getMock('Page\Service\PageService');
        $this->repositoryMock = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $this->repositoryManagerMock = $this->getMock('Versioning\RepositoryManager');
        $this->repositoryServiceMock = $this->getMock('Versioning\Service\RepositoryService');
        $this->objectManagerMock->expects($this->never())
            ->method('flush');
        
        $this->pageManager->setObjectManager($this->objectManagerMock);
        $this->pageManager->setUuidManager($this->uuidManagerMock);
        $this->pageManager->setClassResolver($this->classResolverMock);
        $this->pageManager->setServiceLocator($this->serviceLocatorMock);
        
        
        $this->userMock->expects($this->any())
        ->method('getEntity')
        ->will($this->returnValue($this->getMock('User\Entity\UserInterface')));
        
        $this->pageRepositoryMock->expects($this->any())
        ->method('getEntity')
        ->will($this->returnValue($this->getMock('Page\Entity\PageRepositoryInterface')));
        
        $this->repositoryManagerMock->expects($this->any())
        ->method('addRepository')
        ->will($this->returnValue($this->repositoryManagerMock));
        
        $this->repositoryManagerMock->expects($this->any())
        ->method('getRepository')
        ->will($this->returnValue($this->repositoryServiceMock));
    }

    private function createService()
    {
        $this->classResolverMock->expects($this->atLeastOnce())
            ->method('resolveClassName')
            ->will($this->returnValueMap(array(
            array(
                'Page\Entity\PageRepositoryInterface',
                'Page\Entity\PageRepository'
            ),
            array(
                'Page\Entity\PageRevisionInterface',
                'Page\Entity\PageRevision'
            ),
            array(
                'Page\Service\PageServiceInterface',
                'Page\Service\PageService'
            )
        )));
        
        $this->serviceLocatorMock->expects($this->once())
            ->method('get')
            ->will($this->returnValue($this->pageServiceMock));
        
        $this->pageServiceMock->expects($this->once())
            ->method('setEntity')
            ->with($this->pageRepositoryMock);
        
        $this->pageServiceMock->expects($this->once())
            ->method('setManager')
            ->with($this->pageManager);
        
        $this->pageServiceMock->expects($this->atLeastOnce())
            ->method('getRepositoryManager')
            ->will($this->returnValue($this->repositoryManagerMock));
        
        $this->repositoryManagerMock->expects($this->atLeastOnce())
            ->method('addRepository')
            ->will($this->returnValue($this->repositoryManagerMock));
    }

    private function createPageRevisionEntity()
    {
        $this->classResolverMock->expects($this->any())->method('resolve')->will($this->returnValue($this->pageRevisionMock));
        $this->uuidManagerMock
        ->expects($this->once())
        ->method('injectUuid')
        ->with($this->pageRevisionMock);
        
            
    }
    
    private function createPageRepositoryEntity()
    {
        $this->classResolverMock->expects($this->any())->method('resolve')->will($this->returnValue($this->pageRepositoryMock));
        $this->uuidManagerMock
        ->expects($this->once())
        ->method('injectUuid')
        ->with($this->pageRepositoryMock);
    
    
    }
    
    

    public function testCreateRevision()
    {

     
        $this->createService();     
        $this->createPageRevisionEntity();
        $this->repositoryManagerMock->expects($this->atLeastOnce())
        ->method('getRepository');
    
        $this->assertInstanceOf('Page\Service\PageServiceInterface', $this->pageManager->createRevision($this->pageRepositoryMock, array(
            123,
            456,123,'author'=>$this->userMock
        )));
        

    }
    
    public function testCreatePageRepository()
    {
    
        $this->createPageRepositoryEntity();
        $this->pageRepositoryMock->expects($this->once())->method('setLanguage')->with($this->languageMock);
        $this->createService();
        $this->assertInstanceOf('Page\Service\PageServiceInterface', $this->pageManager->createPageRepository(array(
            123,
            456,123,'language'=>$this->languageMock,'roles'=>array('sysadmin')
        ),$this->languageMock));
    
    
    }
    
}

    