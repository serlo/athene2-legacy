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

use Versioning\RepositoryManager;
use VersioningTest\Entity\RepositoryFake;

/**
 * @codeCoverageIgnore
 */
class RepositoryManagerTest extends \PHPUnit_Framework_TestCase
{
    protected function tearDown ()
    {
        $this->repositoryManager = null;
        parent::tearDown();
    }

    private $repositoryManager;

    public function setUp()
    {
        $this->repositoryManager = new RepositoryManager();
        $this->repositoryManager->setCheckClassInheritance(false);
        
        $serviceLocatorMock = $this->getMock('Zend\ServiceManager\ServiceManager');
        $classResolverMock = $this->getMock('ClassResolver\ClassResolver', array(
            'resolveClassName'
        ));
        $repositoryServiceMock = $this->getMock('Versioning\Service\RepositoryService', array(
            'setIdentifier',
            'setRepository'
        ));
        
        $classResolverMock->expects($this->atLeastOnce())
            ->method('resolveClassName')
            ->will($this->returnValue('Versioning\Service\RepositoryService'));
        $serviceLocatorMock->expects($this->atLeastOnce())
            ->method('get')
            ->will($this->returnValue($repositoryServiceMock));
        $repositoryServiceMock->expects($this->atLeastOnce())
            ->method('setIdentifier');
        $repositoryServiceMock->expects($this->atLeastOnce())
            ->method('setRepository');
        
        $this->repositoryFakes = array();
        for ($i = 1; $i < 5; $i ++) {
            $fake = new RepositoryFake();
            $fake->setId($i);
            $this->repositoryFakes[$i] = $fake;
        }
        
        $this->repositoryManager->setClassResolver($classResolverMock);
        $this->repositoryManager->setServiceLocator($serviceLocatorMock);
    }

    public function testAddRepository()
    {
        $this->repositoryManager->addRepository($this->repositoryFakes[1]);
        $this->assertNotNull($this->repositoryManager->getRepository($this->repositoryFakes[1]));
    }

    public function testAddRepositories()
    {
        $this->repositoryManager->addRepositories($this->repositoryFakes);
        $this->assertNotNull($this->repositoryManager->getRepositories());
    }

    public function testHasRepository()
    {
        $this->repositoryManager->addRepository($this->repositoryFakes[4]);
        $this->assertEquals(true, $this->repositoryManager->hasRepository($this->repositoryFakes[4]));
    }

    public function testRemoveRepository()
    {
        $this->repositoryManager->addRepository($this->repositoryFakes[3]);
        $this->repositoryManager->removeRepository($this->repositoryFakes[3]);
        $this->assertEquals(false, $this->repositoryManager->hasRepository($this->repositoryFakes[3]));
    }
}