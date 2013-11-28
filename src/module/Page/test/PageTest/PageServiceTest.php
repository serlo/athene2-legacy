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
use PageTest\Entity\RevisionFake;

class PageServiceTest extends \PHPUnit_Framework_TestCase
{

    protected $pageService,  $pageRepositoryMock, $pageRevisionMock;

    public function setUp()
    {
        parent::setUp();
        
      
        $this->PageRepositoryMock = $this->getMock('Page\Entity\PageRepository');
        
        $this->pageService = new PageService();
        //$this->entityService->setEntity($this->PageRepositoryMock);
        
        $this->revisionFake = new RevisionFake();
        $this->revisionFake->setId(100);
    }

 public function testGetRevision()
    {
       /* $this->assertNotNull($this->pageService->addRevision($this->revisionFake));
        $this->assertInstanceOf('Page\Entity\PageRevsionInterface', $this->pageService->getRevision(100));
    */
    }
    
    
    
}