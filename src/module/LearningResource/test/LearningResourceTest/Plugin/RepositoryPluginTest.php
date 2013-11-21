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
namespace LearningResourceTest\Plugin;

use LearningResource\Plugin\Repository\RepositoryPlugin;

class RepositoryPluginTest extends \PHPUnit_Framework_TestCase
{

    protected $repository, $repositoryServiceMock, $repositoryManagerMock, $entityServiceMock;

    public function setUp()
    {
        $this->repository = new RepositoryPlugin();
        
        $this->repositoryManagerMock = $this->getMock('Versioning\RepositoryManager');
        $this->repositoryServiceMock = $this->getMock('Versioning\Service\RepositoryService');
        $this->entityServiceMock = $this->getMock('Entity\Service\EntityService');
        $this->entityMock = $this->getMock('Entity\Entity\Entity');
        
        $this->entityServiceMock->expects($this->any())
            ->method('getEntity')
            ->will($this->returnValue($this->entityMock));
        $this->repositoryManagerMock->expects($this->any())
            ->method('addRepository')
            ->will($this->returnValue($this->repositoryManagerMock));
        $this->repositoryManagerMock->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue($this->repositoryServiceMock));
        
        $this->repository->setEntityService($this->entityServiceMock);
        $this->repository->setRepositoryManager($this->repositoryManagerMock);
        
        $this->repository->setConfig(array(
            'revision_form' => 'LearningResourceTest\Plugin\Fake\FormFake',
            'field_order' => array(
                'foo',
                'bar'
            )
        ));
    }

    /**
     * @expectedException \LearningResource\Exception\ClassNotFoundException
     */
    public function testGetFormException()
    {
        $this->repository->setConfig(array(
            'revision_form' => 'notfound'
        ));
        $this->repository->getRevisionForm();
    }

    public function testGetForm()
    {
        $this->assertInstanceOf('LearningResourceTest\Plugin\Fake\FormFake', $this->repository->getRevisionForm());
    }

    public function testHasHead()
    {
        $this->repositoryServiceMock->expects($this->once())
            ->method('hasHead');
        $this->repository->hasHead();
    }

    public function testCountRevisions()
    {
        $this->repositoryServiceMock->expects($this->once())
            ->method('countRevisions');
        $this->repository->countRevisions();
    }

    public function testGetCurrentRevision()
    {
        $this->repositoryServiceMock->expects($this->once())
            ->method('getCurrentRevision');
        $this->repository->getCurrentRevision();
    }

    public function testHasCurrentRevision()
    {
        $this->repositoryServiceMock->expects($this->once())
            ->method('hasCurrentRevision');
        $this->repository->hasCurrentRevision();
    }

    public function testGetRevision()
    {
        $this->repositoryServiceMock->expects($this->once())
            ->method('getRevision');
        $this->repository->getRevision(1);
    }

    public function testCheckout()
    {
        $this->repositoryServiceMock->expects($this->once())
            ->method('checkoutRevision');
        $this->repository->checkout(1);
    }

    public function testIsUnrevised()
    {
        $this->repositoryServiceMock->expects($this->once())
            ->method('isUnrevised');
        $this->repository->isUnrevised();
    }
}