<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author Jakob Pfab (jakob.pfab@serlo.org)
 * @author	Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace PageTest\Controller;

use AtheneTest\Controller\Athene2ApplicationTestCase;

class PageControllerTest extends Athene2ApplicationTestCase

{

    protected $pageRepositoryMock,$objectManagerMock,$controller,$pageManagerMock,$pageServiceMock,$pageRevisionMock;

    public function setUp()
    {
        parent::setUp();
        $this->objectManagerMock = $this->getMock('Doctrine\ORM\EntityManager', [], [], '', false);
        $this->pageManagerMock = $this->getMock('Page\Manager\PageManager');
        $this->pageServiceMock = $this->getMock('Page\Service\PageService');
        $this->pageRevisionMock = $this->getMock('Page\Entity\PageRevision');
        $this->controller = $this->getApplicationServiceLocator()->get('Page\Controller\IndexController');
        $this->controller->setPageManager($this->pageManagerMock);
        $this->controller->setObjectManager($this->objectManagerMock);
        $this->pageRepositoryMock = $this->getMock('Page\Entity\PageRepository');
    }

    private function getPageService(){
        $this->pageManagerMock->expects($this->once())->method('getPageRepository')->will($this->returnValue($this->pageServiceMock));
    }
    public function testIndexAction() {
        $this->controller->getPageManager()
        ->expects($this->once())
        ->method('findAllRepositorys')
        ->will($this->returnValue([]));
        $this->dispatch('/page');
        $this->assertResponseStatusCode(200);
        
    }
    
    public function testSetCurrentRevisionAction(){
        /*
        $this->getPageService();
        $this->pageManagerMock->expects($this->once())->method('getRevision')->will($this->returnValue($this->pageRevisionMock));
        $this->pageServiceMock->expects($this->once())->method('setCurrentRevision')
        ->with($this->pageRevisionMock);
        
        $this->pageServiceMock->expects($this->once())->method('getRepositoryId')->will($this->returnValue(66));
        $this->controller->getObjectManager()->expects($this->once())->method('persist')->with($this->pageRepositoryMock);
        $this->controller->getObjectManager()->expects($this->once())->method('flush');
        $this->dispatch('/page/view/66/67/setcurrent');
        $this->assertResponseStatusCode(302);*/
    }
  
}