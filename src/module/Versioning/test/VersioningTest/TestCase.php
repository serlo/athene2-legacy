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

use VersioningTest\Entity\RevisionFake;
use VersioningTest\Entity\RepositoryFake;
use Versioning\RepositoryManager;
use AtheneTest\Bootstrap as AtheneBoostrap;
use AtheneTest\TestCase\ObjectManagerTestCase;

abstract class TestCase extends ObjectManagerTestCase
{
    protected $repositoryManager, $repositories = array(), $date, $revisions = array();
    
    public function setUp(){
        $sm = AtheneBoostrap::getServiceManager();
        $repositoryManager = new RepositoryManager();
        
        $this->repositories[] = new RepositoryFake();
        $this->repositories[] = new RepositoryFake();
        $this->date = new \DateTime('NOW');
        
        $this->hydrateRepository($this->repositories[0], 1);
        $this->hydrateRepository($this->repositories[1], 4);
        
        $repositoryManager->setServiceLocator($sm);
        
        $this->repositoryManager = $repositoryManager;
    }
    
    private function hydrateRepository($repository, $offset = 1){
        $user = new \User\Entity\User();
        
        $revision =  new RevisionFake();
        $revision->setId($offset);
        $revision->setRepository($repository);
        $revision->setAuthor($user);
        $revision->setDate($this->date);
        $revision->set('foo', 'bar');
        $this->revisions[] = $revision;
        
        $repository->addRevision($revision);
        
        $revision =  new RevisionFake();
        $revision->setId($offset+1);
        $revision->setRepository($repository);
        $revision->setAuthor($user);
        $revision->setDate($this->date);
        $revision->set('foo', 'bar2');
        $this->revisions[] = $revision;
        
        $repository->addRevision($revision);
        
        $revision =  new RevisionFake();
        $revision->setId($offset+2);
        $revision->setRepository($repository);
        $revision->setAuthor($user);
        $revision->setDate($this->date);
        $revision->set('foo', 'bar3');
        $this->revisions[] = $revision;
        
        $repository->addRevision($revision);
    }
}