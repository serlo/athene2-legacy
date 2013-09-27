<?php
/**
 *
 *
 *
 *
 *
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license LGPL-3.0
 * @license http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace UserTest;

use User\Manager\UserManager;

class UserManagerTest extends \PHPUnit_Framework_TestCase
{

    protected $userManager;

    public function setUp()
    {
        $this->userManager = new UserManager();
        $classResolverMock = $this->getMock('ClassResolver\ClassResolver');
        $entityManagerMock = $this->getMock('Doctrine\ORM\EntityManager', array(), array(), '', false);
        $serviceLocatorMock = $this->getMock('Zend\ServiceManager\ServiceManager');
        $repositoryMock = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $userMock = $this->getMock('User\Entity\User');
        $userServiceMock = $this->getMock('User\Service\UserService');
        $authServiceMock = $this->getMock('Zend\Authentication\AuthenticationService');
        $eventManagerMock = $this->getMock('Zend\EventManager\EventManager');
        
        $classResolverMock->expects($this->any())
            ->method('resolveClassName')
            ->will($this->returnValue('User\Entity\User'));
        $serviceLocatorMock->expects($this->any())
            ->method('get')
            ->will($this->returnValue($userServiceMock));
        $entityManagerMock->expects($this->any())
            ->method('find')
            ->will($this->returnValue($userMock));
        $repositoryMock->expects($this->any())
            ->method('findOneBy')
            ->will($this->returnValue($userMock));
        $entityManagerMock->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue($repositoryMock));
        $userServiceMock->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(1));
        $userMock->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(1));
        $authServiceMock->expects($this->any())
            ->method('getIdentity')
            ->will($this->returnValue('foo@bar.de'));
        $authServiceMock->expects($this->any())
            ->method('hasIdentity')
            ->will($this->returnValue(1));
        
        $this->userManager->setAuthenticationService($authServiceMock);
        $this->userManager->setCheckClassInheritance(false);
        $this->userManager->setClassResolver($classResolverMock);
        $this->userManager->setEventManager($eventManagerMock);
        $this->userManager->setObjectManager($entityManagerMock);
        $this->userManager->setServiceLocator($serviceLocatorMock);
        
        $this->userServiceMock = $userServiceMock;
        $this->authServiceMock = $authServiceMock;
    }

    public function testGetUser()
    {
        $this->assertEquals(1, $this->userManager->getUser(1)
            ->getId());
    }

    public function testCreateUser()
    {
        $this->assertEquals(1, $this->userManager->createUser(array())
            ->getId());
    }

    public function testFindUserByEmail()
    {
        $user = $this->userManager->findUserByEmail('foo@bar.de');
        $this->assertEquals(1, $user->getId());
    }

    public function testFindUserByUsername()
    {
        $user = $this->userManager->findUserByUsername('foo@bar.de');
        $this->assertEquals(1, $user->getId());
    }

    public function testGetUserFromAuthenticator()
    {
        $this->userServiceMock->expects($this->once())
            ->method('getRemoved')
            ->will($this->returnValue(false));
        $this->userServiceMock->expects($this->once())
            ->method('hasRole')
            ->will($this->returnValue(true));
        $this->authServiceMock->expects($this->never())
            ->method('clearIdentity');
        $user = $this->userManager->getUserFromAuthenticator();
        $this->assertEquals(1, $user->getId());
    }

    public function testGetUserFromAuthenticatorFailsBecauseRemoved()
    {
        $this->userServiceMock->expects($this->once())
            ->method('getRemoved')
            ->will($this->returnValue(false));
        $this->userServiceMock->expects($this->once())
            ->method('hasRole')
            ->will($this->returnValue(false));
        $this->authServiceMock->expects($this->once())
            ->method('clearIdentity');
        $user = $this->userManager->getUserFromAuthenticator();
        $this->assertEquals(null, $user);
    }
    
    public function testTrashUser(){
        $this->userServiceMock->expects($this->once())->method('setRemoved');
        $this->userManager->trashUser(1);
    }
}