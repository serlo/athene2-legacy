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
namespace LearningResourceTest\Plugin\Controller;

use AtheneTest\Controller\DefaultLayoutTestCase;

class RepositoryPluginControllerTest extends DefaultLayoutTestCase
{

    protected $entityServiceMock, $repositoryPluginMock;

    public function setUp()
    {
        parent::setUp();
        
        $controller = $this->getApplicationServiceLocator()->get('LearningResource\Plugin\Repository\Controller\RepositoryController');
        $controller->setEntityManager($this->getMock('Entity\Manager\EntityManager'));
        $controller->setUserManager($this->getMock('User\Manager\UserManager'));
        $this->entityServiceMock = $this->getMock('Entity\Service\EntityService', array(
            'isPluginWhitelisted',
            'repository',
            'getId',
            'getEntity'
        ));
        $this->repositoryPluginMock = $this->getMock('LearningResource\Plugin\Repository\RepositoryPlugin');
        
        $controller->getEntityManager()
            ->expects($this->atLeastOnce())
            ->method('getEntity')
            ->will($this->returnValue($this->entityServiceMock));
        
        $this->entityServiceMock->expects($this->any())
            ->method('getEntity')
            ->will($this->returnValue($this->getMock('Entity\Entity\Entity')));
        
        $this->entityServiceMock->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(1));
        
        $this->entityServiceMock->expects($this->atLeastOnce())
            ->method('isPluginWhitelisted')
            ->will($this->returnValue(true));
        
        $this->entityServiceMock->expects($this->atLeastOnce())
            ->method('repository')
            ->will($this->returnValue($this->repositoryPluginMock));
        
        $controller->getUserManager()
            ->expects($this->any())
            ->method('getUserFromAuthenticator')
            ->will($this->returnValue('User\Service\UserService'));
    }

    public function testAddRevisionAction()
    {
        $this->setUpFirewall();
        
        $this->repositoryPluginMock->expects($this->once())
            ->method('getRevisionForm')
            ->will($this->returnValue(new \LearningResource\Form\ArticleForm()));
        
        $this->dispatch('/entity/repository/add-revision/1');
        
        $this->assertResponseStatusCode(200);
    }

    public function testCompareAction()
    {
        $this->setUpFirewall();
        
        $this->repositoryPluginMock->expects($this->atLeastOnce())
            ->method('getRevision')
            ->will($this->returnValue(new \Entity\Entity\Revision()));
        
        $this->repositoryPluginMock->expects($this->atLeastOnce())
            ->method('getCurrentRevision')
            ->will($this->returnValue(new \Entity\Entity\Revision()));
        
        $this->dispatch('/entity/repository/compare/1/3');
        
        $this->assertResponseStatusCode(200);
    }
}