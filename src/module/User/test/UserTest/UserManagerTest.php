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

class UserManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UserManager
     */
    protected $userManager;
    
    
    public function setUp(){
        $sm = AtheneBootstrap::getServiceManager();
        $userManager = new UserManager();
        
        $userManager->setServiceLocator($sm);
        $userManager->setObjectManager($sm->get('Doctrine\ORM\EntityManager'));
        $userManager->setClassResolver($sm->get('ClassResolver\ClassResolver'));
        
        $this->userManager = $userManager;
    }
    
    public function testGet(){
        $this->assertEquals('1', $this->userManager->get(1)->getId());
        $this->assertEquals('1', $this->userManager->get('aeneas@q-mail.me')->getId());
        $this->assertEquals('1', $this->userManager->get('arekkas')->getId());
    }
    
    public function testRepositories(){
        $this->assertNotNull($this->userManager->findAllRoles());
        $this->assertNotNull($this->userManager->findAllUsers());
    }
    
    public function testCreate(){
        $this->userManager->setObjectManager($this->getEmMock());
        
        $entity = $this->userManager->create(array(
            'username' => 'test',
            'email' => 'test@test.de',
            'password' => 'foobar',
            'language' => 1,
            'id' => 1
        ));
        
        $this->assertInstanceOf('User\Service\UserServiceInterface', $entity);
    }

    protected function getEmMock ()
    {
        $emMock = $this->getMock('\Doctrine\ORM\EntityManager', array(
            'getRepository',
            'getClassMetadata',
            'persist',
            'flush',
            'find'
        ), array(), '', false);
        $emMock->expects($this->any())
            ->method('getClassMetadata')
            ->will($this->returnValue((object) array(
            'name' => 'aClass'
        )));
        $emMock->expects($this->any())
            ->method('persist')
            ->will($this->returnValue(null));
        $emMock->expects($this->any())
            ->method('flush')
            ->will($this->returnValue(null));
        return $emMock; // it
                        // tooks
                        // 13
                        // lines
                        // to
                        // achieve
                        // mock!
    }
}