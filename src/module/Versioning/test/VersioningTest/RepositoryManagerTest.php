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

class RepositoryManagerTest extends TestCase
{

    public function testAddRepository ()
    {
        $this->repositoryManager->addRepository($this->repositories[0]);
        $this->assertNotNull($this->repositoryManager->getRepository($this->repositories[0]));
    }
    
    public function testAddRepositories ()
    {
        $this->repositoryManager->addRepositories($this->repositories);
        $this->assertNotNull($this->repositoryManager->getRepository($this->repositories[0]));
        $this->assertNotNull($this->repositoryManager->getRepository($this->repositories[1]));
    }
    
    public function testHasRepository(){
        $this->repositoryManager->addRepository($this->repositories[0]);
        $this->assertEquals(true, $this->repositoryManager->hasRepository($this->repositories[0]));        
    }
    
    public function testRemoveRepository(){
        $this->repositoryManager->addRepository($this->repositories[0]);
        $this->repositoryManager->removeRepository($this->repositories[0]);
        $this->assertEquals(false, $this->repositoryManager->hasRepository($this->repositories[0]));              
    }
}