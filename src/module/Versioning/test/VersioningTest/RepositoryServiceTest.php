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
namespace VersioningTest;

use Versioning\Service\RepositoryService;
use VersioningTest\Entity\RepositoryFake;
use VersioningTest\Entity\RevisionFake;
class RepositoryServiceTest extends \PHPUnit_Framework_TestCase
{
    private $repositoryService, $revisionFake;
    
    protected function tearDown ()
    {
        $this->repositoryService = null;
        parent::tearDown();
    }

    public function setUp(){
        $this->repositoryService = new RepositoryService();
        
        $eventManagerMock = $this->getMock('Zend\EventManager\EventManager', array('attach', 'trigger'));
        $repository = new RepositoryFake();
        $repository->setId(1);
        
        $this->revisionFake = new RevisionFake();
        $this->revisionFake->setId(100);
        
        $this->repositoryService->setRepository($repository);
        $this->repositoryService->setEventManager($eventManagerMock);
    }
    
    public function testAddRevision(){
        $this->assertNotNull($this->repositoryService->addRevision($this->revisionFake));
        $this->assertEquals(true, $this->repositoryService->hasRevision(100));
    }
    
    public function testRemoveRevision(){
        $this->assertNotNull($this->repositoryService->addRevision($this->revisionFake));
        $this->assertNotNull($this->repositoryService->removeRevision(100));
        $this->assertEquals(false, $this->repositoryService->hasRevision(100));
    }
    
    public function testGetRevision(){
        $this->assertNotNull($this->repositoryService->addRevision($this->revisionFake));
        $this->assertInstanceOf('Versioning\Entity\RevisionInterface', $this->repositoryService->getRevision(100));
    }
    
    public function testGetHead(){
        $this->assertNotNull($this->repositoryService->addRevision($this->revisionFake));
        $this->assertInstanceOf('Versioning\Entity\RevisionInterface', $this->repositoryService->getHead());
    }
    
    public function testCheckoutRevision(){
        $this->assertNotNull($this->repositoryService->addRevision($this->revisionFake));
        $this->assertNotNull($this->repositoryService->checkoutRevision(100));
        $this->assertEquals(100, $this->repositoryService->getCurrentRevision()->getId());
        $this->assertEquals(true, $this->repositoryService->hasCurrentRevision());
    }
}