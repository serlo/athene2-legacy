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
namespace VersioningTest\Service;

use VersioningTest\TestCase;
use Versioning\Service\RepositoryService;
use AtheneTest\Bootstrap;
use VersioningTest\Entity\UserFake;

class RepositoryServiceTest extends TestCase
{

    protected $repositoryService;

    public function setUp ()
    {
        parent::setUp();
        
        $authService = $this->getMock('Auth\Service\AuthService');
        $authService->expects($this->any())
            ->method('getUser')
            ->will($this->returnValue(new UserFake()));
        
        $this->repositoryService = new RepositoryService();
        $this->repositoryService->setIdentifier('FooService');
        $this->repositoryService->setRepository($this->repositories[0]);
        $this->repositoryService->setAuthService($authService);
        $this->repositoryService->setObjectManager(Bootstrap::getServiceManager()->get('doctrine.entitymanager.orm_default'));
        $this->repositoryService->setEventManager(Bootstrap::getServiceManager()->get('EventManager'));
    }

    public function testCurrentRevision ()
    {
        $this->repositoryService->checkoutRevision($this->revisions[1]);
        $this->assertEquals($this->revisions[1], $this->repositoryService->getCurrentRevision());
    }

    public function testGetRevision ()
    {
        $this->repositoryService->addRevision($this->revisions[4]);
        $this->repositoryService->addRevision($this->revisions[5]);
        $this->assertEquals($this->revisions[4], $this->repositoryService->getRevision($this->revisions[4]->getId()));
        $this->assertEquals($this->revisions[5], $this->repositoryService->getRevision($this->revisions[5]->getId()));
    }

    public function testAddRevision ()
    {
        $this->repositoryService->addRevision($this->revisions[4]);
        $this->assertEquals(true, $this->repositoryService->hasRevision($this->revisions[4]->getId()));
    }

    public function testHasRevision ()
    {
        $this->repositoryService->addRevision($this->revisions[5]);
        $this->assertEquals(true, $this->repositoryService->hasRevision($this->revisions[5]));
        $this->assertEquals(true, $this->repositoryService->hasRevision($this->revisions[5]->getId()));
    }

    public function testRemoveRevision ()
    {
        $this->repositoryService->removeRevision($this->revisions[1]);
        $this->assertEquals(false, $this->repositoryService->hasRevision($this->revisions[1]));
        $this->assertEquals(false, $this->repositoryService->hasRevision($this->revisions[1]->getId()));
    }

    public function testGetHead ()
    {
        $this->repositoryService->addRevision($this->revisions[4]);
        $this->assertEquals($this->revisions[4], $this->repositoryService->getHead());
    }
}