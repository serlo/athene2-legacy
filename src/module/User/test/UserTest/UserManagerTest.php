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
namespace UserTest;

use User\Manager\UserManager;
use AtheneTest\Bootstrap as AtheneBootstrap;
use AtheneTest\TestCase\ObjectManagerTestCase;
use User\Entity\User;
use AtheneTest\Bootstrap;

class UserManagerTest extends ObjectManagerTestCase
{

    /**
     *
     * @var UserManager
     */
    protected $userManager;
    
    private $user;

    public function setUp ()
    {
        parent::setUp();
        
        $sm = AtheneBootstrap::getServiceManager();
        $userManager = new UserManager();
        
        $userManager->setServiceLocator($sm);
        $userManager->setObjectManager($sm->get('Doctrine\ORM\EntityManager'));
        $userManager->setClassResolver($sm->get('ClassResolver\ClassResolver'));
        
        $repository = new UserRepositoryFake();
        $this->hydrateObjectManager();
        $this->injectEntityRepository($repository);
        
        $this->userManager = $userManager;
    }

    public function testGetById ()
    {
        $this->assertEquals('1', $this->userManager->get(1)
            ->getId());
    }

    public function testGetByUsername ()
    {
        $this->assertEquals('1', $this->userManager->get('foobar')
            ->getId());
    }

    public function testGetByEmail ()
    {
        $this->assertEquals('1', $this->userManager->get('foo@bar.de')
            ->getId());
    }

    public function testRepositories ()
    {
        $this->assertNotNull($this->userManager->findAllRoles());
        $this->assertNotNull($this->userManager->findAllUsers());
    }

    public function testCreate ()
    {        
        $entity = $this->userManager->create(array(
            'username' => 'test',
            'email' => 'test@test.de',
            'password' => 'foobar',
            'language' => 1,
            'id' => 2
        ));
        
        $this->assertInstanceOf('User\Service\UserServiceInterface', $entity);
    }
    
    private function hydrateObjectManager ()
    {
        Bootstrap::getServiceManager()->get('Doctrine\ORM\EntityManager')->expects($this->any())
            ->method('find')
            ->with($this->equalTo('User\Entity\User'), $this->equalTo(1))
            ->will($this->returnValue($this->getUserFake()));
    }

    private function getUserFake ()
    {
        if (! $this->user) {
            $user = new User();
            $user->setId(1);
            $this->user = $user;
        }
        return $this->user;
    }
}