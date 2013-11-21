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
namespace UserTest\Controller;

use AtheneTest\Controller\Athene2ApplicationTestCase;

class UserControllerTest extends Athene2ApplicationTestCase
{

    protected $userManagerMock, $authAdapterMock, $authServiceMock, $registerForm, $objectManagerMock, $repositoryMock, $eventManagerMock, $languageService;

    public function setUp()
    {
        parent::setUp();
        $this->setUpFirewall();
        
        /* Detach listeners */
        $this->detachAggregatedListener('Mailman\Listener\UserControllerListener');
        $this->detachAggregatedListener('Event\Listener\UserControllerListener');
        
        $this->userManagerMock = $this->getMock('User\Manager\UserManager');
        $this->authAdapterMock = $this->getMock('User\Authentication\Adapter\UserAuthAdapter');
        $this->authServiceMock = $this->getMock('Zend\Authentication\AuthenticationService');
        $this->objectManagerMock = $this->getMock('Doctrine\ORM\EntityManager', array(), array(), '', false);
        $this->repositoryMock = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $this->eventManagerMock = $this->getMock('Zend\EventManager\EventManager');
        $this->languageManagerMock = $this->getMock('Language\Manager\LanguageManager');
        
        $this->objectManagerMock->expects($this->atLeastOnce())
            ->method('getRepository')
            ->will($this->returnValue($this->repositoryMock));
        $this->registerForm = new \User\Form\Register($this->objectManagerMock);
        $this->userManagerMock->expects($this->any())
            ->method('getObjectManager')
            ->will($this->returnValue($this->objectManagerMock));
        $this->userManagerMock->expects($this->any())
            ->method('getEventManager')
            ->will($this->returnValue($this->eventManagerMock));
        $this->languageService = $this->getMock('Language\Service\LanguageService');
        $this->languageManagerMock->expects($this->any())
            ->method('getLanguageFromRequest')
            ->will($this->returnValue($this->languageService));
        $this->languageService->expects($this->any())
            ->method('getEntity')
            ->will($this->returnValue($this->getMock('Language\Entity\Language')));
        
        $controller = $this->getApplicationServiceLocator()->get('User\Controller\UserController');
        $controller->setUserManager($this->userManagerMock);
        $controller->setAuthAdapter($this->authAdapterMock);
        $controller->setAuthenticationService($this->authServiceMock);
        $controller->setForm('register', $this->registerForm);
        $controller->setLanguageManager($this->languageManagerMock);
    }

    public function testLogOutAction()
    {
        $this->authServiceMock->expects($this->once())
            ->method('clearIdentity');
        $this->dispatch('/user/logout');
        $this->assertResponseStatusCode(302);
    }

    public function testLoginActionForm()
    {
        $this->dispatch('/user/login');
        
        $this->assertResponseStatusCode(200);
    }

    public function testLoginAction()
    {
        $resultMock = $this->getMockBuilder('Zend\Authentication\Result')
            ->disableOriginalConstructor()
            ->getMock();
        $userServiceMock = $this->getMocK('User\Service\UserService');
        
        $this->authAdapterMock->expects($this->once())
            ->method('setIdentity')
            ->with('user');
        $this->authAdapterMock->expects($this->once())
            ->method('setPassword')
            ->with('pass');
        $this->authServiceMock->expects($this->once())
            ->method('authenticate')
            ->will($this->returnValue($resultMock));
        $resultMock->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $this->userManagerMock->expects($this->once())
            ->method('findUserByEmail')
            ->will($this->returnValue($userServiceMock));
        $userServiceMock->expects($this->once())
            ->method('updateLoginData');
        $this->userManagerMock->expects($this->once())
            ->method('getObjectManager')
            ->will($this->returnValue($this->objectManagerMock));
        $this->objectManagerMock->expects($this->once())
            ->method('flush');
        
        $this->dispatch('/user/login', 'POST', array(
            'email' => 'user',
            'password' => 'pass'
        ));
        
        $this->assertResponseStatusCode(302);
    }

    public function testLoginActionFails()
    {
        $resultMock = $this->getMockBuilder('Zend\Authentication\Result')
            ->disableOriginalConstructor()
            ->getMock();
        
        $this->authAdapterMock->expects($this->once())
            ->method('setIdentity')
            ->with('user');
        $this->authAdapterMock->expects($this->once())
            ->method('setPassword')
            ->with('pass');
        $this->authServiceMock->expects($this->once())
            ->method('authenticate')
            ->will($this->returnValue($resultMock));
        $resultMock->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false));
        
        $this->dispatch('/user/login', 'POST', array(
            'email' => 'user',
            'password' => 'pass'
        ));
        
        $this->assertResponseStatusCode(200);
    }

    public function testRegisterAction()
    {
        $this->authServiceMock->expects($this->once())
            ->method('hasIdentity')
            ->will($this->returnValue(false));
        
        $this->dispatch('/user/register');
        $this->assertResponseStatusCode(200);
    }

    public function testRegisterActionWithIdentity()
    {
        $this->authServiceMock->expects($this->once())
            ->method('hasIdentity')
            ->will($this->returnValue(true));
        
        $this->dispatch('/user/register');
        $this->assertResponseStatusCode(302);
    }

    public function testRegisterActionWithPost()
    {
        $data = array(
            'username' => '1234',
            'password' => '5431',
            'email' => 'fa98s'
        );
        
        $this->authServiceMock->expects($this->once())
            ->method('hasIdentity')
            ->will($this->returnValue(false));
        
        $this->userManagerMock->expects($this->any())
            ->method('createUser')
            ->will($this->returnValue($this->getMock('User\Entity\User')));
        
        $registerFormMock = $this->getMockBuilder('User\Form\Register')
            ->disableOriginalConstructor()
            ->getMock();
        $registerFormMock->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $registerFormMock->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($data));
        $registerFormMock->expects($this->once())
            ->method('setData')
            ->with($data);
        $this->objectManagerMock->expects($this->once())
            ->method('flush');
        
        $controller = $this->getApplicationServiceLocator()->get('User\Controller\UserController');
        $controller->setForm('register', $registerFormMock);
        
        $this->dispatch('/user/register', 'POST', $data);
        $this->assertResponseStatusCode(302);
    }

    public function testRegisterActionWithInvalidPost()
    {        
        $this->dispatch('/user/register', 'POST', array());
        $this->assertResponseStatusCode(200);
    }

    public function testRestorePassword()
    {
        $this->dispatch('/user/password/restore');
        $this->assertResponseStatusCode(200);
    }

    public function testRestorePasswordWithPost()
    {
        $user = $this->getMock('User\Service\UserService');
        /* @var $controller \User\Controller\UserController */
        $controller = $this->getApplicationServiceLocator()->get('User\Controller\UserController');
        $controller->getUserManager()
            ->expects($this->once())
            ->method('findUserByEmail')
            ->will($this->returnValue($user));
        $user->expects($this->once())
            ->method('generateToken');
        $controller->getObjectManager()
            ->expects($this->once())
            ->method('flush');
        $this->dispatch('/user/password/restore', 'POST', array(
            'email' => 'foo@bar.com'
        ));
        $this->assertResponseStatusCode(302);
    }
    
    /* @expectedException \User\Exception\UserNotFoundException */
    public function testRestorePasswordWithPostUserNotFoundException()
    {
        $user = $this->getMock('User\Service\UserService');
        /* @var $controller \User\Controller\UserController */
        $controller = $this->getApplicationServiceLocator()->get('User\Controller\UserController');
        $controller->getUserManager()
            ->expects($this->once())
            ->method('findUserByEmail')
            ->will($this->throwException(new \User\Exception\UserNotFoundException()));
        $this->dispatch('/user/password/restore', 'POST', array(
            'email' => 'foo@bar.com'
        ));
        $this->assertResponseStatusCode(200);
    }

    public function testRestorePasswordWithInvalidPost()
    {
        $this->dispatch('/user/password/restore', 'POST', array());
        $this->assertResponseStatusCode(200);
    }

    public function testRestorePasswordWithToken()
    {
        $this->dispatch('/user/password/restore/foobar');
        $this->assertResponseStatusCode(200);
    }

    public function testRestorePasswordWithTokenAndInvalidPost()
    {
        $this->dispatch('/user/password/restore/foobar', 'POST', array());
        $this->assertResponseStatusCode(200);
    }

    public function testRestorePasswordWithTokenAndPost()
    {
        $user = $this->getMock('User\Service\UserService');
        /* @var $controller \User\Controller\UserController */
        $controller = $this->getApplicationServiceLocator()->get('User\Controller\UserController');
        $controller->getUserManager()
            ->expects($this->once())
            ->method('findUserByToken')
            ->will($this->returnValue($user));
        $user->expects($this->once())
            ->method('setPassword');
        $user->expects($this->once())
            ->method('generateToken');
        $controller->getObjectManager()
            ->expects($this->once())
            ->method('flush');
        $this->dispatch('/user/password/restore/foobar', 'POST', array(
            'password' => 'abcdef',
            'passwordConfirm' => 'abcdef'
        ));
        $this->assertResponseStatusCode(302);
    }

    public function testActivate()
    {
        $user = $this->getMock('User\Service\UserService');
        /* @var $controller \User\Controller\UserController */
        $controller = $this->getApplicationServiceLocator()->get('User\Controller\UserController');
        $controller->getUserManager()
            ->expects($this->once())
            ->method('findUserByToken')
            ->will($this->returnValue($user));
        $user->expects($this->once())
            ->method('addRole')
            ->with(2);
        $user->expects($this->once())
            ->method('generateToken');
        $controller->getObjectManager()
            ->expects($this->once())
            ->method('flush');
        $this->dispatch('/user/activate/foobar');
        $this->assertResponseStatusCode(302);
    }

    public function testMe()
    {
        $user = $this->getMock('User\Service\UserService');
        /* @var $controller \User\Controller\UserController */
        $controller = $this->getApplicationServiceLocator()->get('User\Controller\UserController');
        $controller->getUserManager()
            ->expects($this->once())
            ->method('getUserFromAuthenticator')
            ->will($this->returnValue($user));
        $user->expects($this->atLeastOnce())
            ->method('getRoles')
            ->will($this->returnValue(array()));
        $user->expects($this->atLeastOnce())
            ->method('getUnassociatedRoles')
            ->will($this->returnValue(array()));
        $this->dispatch('/user/me');
        $this->assertResponseStatusCode(200);
    }

    public function testChangePassword()
    {
        $user = $this->getMock('User\Service\UserService');
        /* @var $controller \User\Controller\UserController */
        $controller = $this->getApplicationServiceLocator()->get('User\Controller\UserController');
        $controller->getUserManager()
            ->expects($this->once())
            ->method('getUserFromAuthenticator')
            ->will($this->returnValue($user));
        $this->dispatch('/user/password/change');
        $this->assertResponseStatusCode(200);
    }

    public function testChangePasswordWithInvalidPost()
    {
        $user = $this->getMock('User\Service\UserService');
        /* @var $controller \User\Controller\UserController */
        $controller = $this->getApplicationServiceLocator()->get('User\Controller\UserController');
        $controller->getUserManager()
            ->expects($this->once())
            ->method('getUserFromAuthenticator')
            ->will($this->returnValue($user));
        $this->dispatch('/user/password/change', 'POST', array());
        $this->assertResponseStatusCode(200);
    }

    public function testChangePasswordWithPostAndInvalidCredentials()
    {
        $user = $this->getMock('User\Service\UserService');
        /* @var $controller \User\Controller\UserController */
        $controller = $this->getApplicationServiceLocator()->get('User\Controller\UserController');
        $controller->getUserManager()
            ->expects($this->once())
            ->method('getUserFromAuthenticator')
            ->will($this->returnValue($user));
        $resultMock = $this->getMockBuilder('Zend\Authentication\Result')
            ->disableOriginalConstructor()
            ->getMock();
        $controller->getAuthAdapter()
            ->expects($this->once())
            ->method('setIdentity');
        $controller->getAuthAdapter()
            ->expects($this->once())
            ->method('setPassword');
        $controller->getAuthAdapter()
            ->expects($this->once())
            ->method('authenticate')
            ->will($this->returnValue($resultMock));
        $resultMock->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false));
        $resultMock->expects($this->once())
            ->method('getMessages')
            ->will($this->returnValue(array()));
        
        $this->dispatch('/user/password/change', 'POST', array(
            'currentPassword' => 'abcd',
            'password' => 'foobar',
            'passwordConfirm' => 'foobar'
        ));
        $this->assertResponseStatusCode(200);
    }

    public function testChangePasswordWithPost()
    {
        $user = $this->getMock('User\Service\UserService');
        /* @var $controller \User\Controller\UserController */
        $controller = $this->getApplicationServiceLocator()->get('User\Controller\UserController');
        $controller->getUserManager()
            ->expects($this->once())
            ->method('getUserFromAuthenticator')
            ->will($this->returnValue($user));
        $resultMock = $this->getMockBuilder('Zend\Authentication\Result')
            ->disableOriginalConstructor()
            ->getMock();
        $controller->getAuthAdapter()
            ->expects($this->once())
            ->method('setIdentity');
        $controller->getAuthAdapter()
            ->expects($this->once())
            ->method('setPassword');
        $controller->getAuthAdapter()
            ->expects($this->once())
            ->method('authenticate')
            ->will($this->returnValue($resultMock));
        $resultMock->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $user->expects($this->once())
            ->method('setPassword');
        $controller->getObjectManager()
            ->expects($this->once())
            ->method('persist');
        $controller->getObjectManager()
            ->expects($this->once())
            ->method('flush');
        
        $this->dispatch('/user/password/change', 'POST', array(
            'currentPassword' => 'abcd',
            'password' => 'foobar',
            'passwordConfirm' => 'foobar'
        ));
        $this->assertResponseStatusCode(302);
    }

    public function testProfile()
    {
        $user = $this->getMock('User\Service\UserService');
        /* @var $controller \User\Controller\UserController */
        $controller = $this->getApplicationServiceLocator()->get('User\Controller\UserController');
        $controller->getUserManager()
            ->expects($this->once())
            ->method('getUser')
            ->will($this->returnValue($user));
        $user->expects($this->atLeastOnce())
            ->method('getRoles')
            ->will($this->returnValue(array()));
        $user->expects($this->atLeastOnce())
            ->method('getUnassociatedRoles')
            ->will($this->returnValue(array()));
        $this->dispatch('/user/profile/1');
        $this->assertResponseStatusCode(200);
    }

    public function testRemove()
    {
        /* @var $controller \User\Controller\UserController */
        $controller = $this->getApplicationServiceLocator()->get('User\Controller\UserController');
        $controller->getUserManager()
            ->expects($this->once())
            ->method('trashUser');
        $controller->getObjectManager()
            ->expects($this->once())
            ->method('flush');
        
        $this->dispatch('/user/remove/1');
        $this->assertResponseStatusCode(302);
    }

    public function testPurge()
    {
        /* @var $controller \User\Controller\UserController */
        $controller = $this->getApplicationServiceLocator()->get('User\Controller\UserController');
        $controller->getUserManager()
            ->expects($this->once())
            ->method('purgeUser');
        $controller->getObjectManager()
            ->expects($this->once())
            ->method('flush');
        
        $this->dispatch('/user/purge/1');
        $this->assertResponseStatusCode(302);
    }

    public function testRemoveRole()
    {
        $user = $this->getMock('User\Service\UserService');
        /* @var $controller \User\Controller\UserController */
        $controller = $this->getApplicationServiceLocator()->get('User\Controller\UserController');
        $controller->getUserManager()
            ->expects($this->once())
            ->method('getUser')
            ->will($this->returnValue($user));
        $user->expects($this->once())
            ->method('removeRole');
        $controller->getObjectManager()
            ->expects($this->once())
            ->method('flush');
        
        $this->dispatch('/user/1/role/remove/2');
        $this->assertResponseStatusCode(302);
    }

    public function testAddRole()
    {
        $user = $this->getMock('User\Service\UserService');
        /* @var $controller \User\Controller\UserController */
        $controller = $this->getApplicationServiceLocator()->get('User\Controller\UserController');
        $controller->getUserManager()
            ->expects($this->once())
            ->method('getUser')
            ->will($this->returnValue($user));
        $user->expects($this->once())
            ->method('addRole');
        $controller->getObjectManager()
            ->expects($this->once())
            ->method('flush');
        
        $this->dispatch('/user/1/role/add/2');
        $this->assertResponseStatusCode(302);
    }
    
    public function testSettings(){
        $user = $this->getMock('User\Service\UserService');
        /* @var $controller \User\Controller\UserController */
        $controller = $this->getApplicationServiceLocator()->get('User\Controller\UserController');
        $controller->getUserManager()
            ->expects($this->once())
            ->method('getUserFromAuthenticator')
            ->will($this->returnValue($user));
        
        $this->dispatch('/user/settings');
        $this->assertResponseStatusCode(200);
    }
    
    public function testSettingsWithPost(){
        $user = $this->getMock('User\Service\UserService');
        /* @var $controller \User\Controller\UserController */
        $controller = $this->getApplicationServiceLocator()->get('User\Controller\UserController');
        $controller->getUserManager()
            ->expects($this->once())
            ->method('getUserFromAuthenticator')
            ->will($this->returnValue($user));
        $controller->getObjectManager()->expects($this->once())->method('persist');
        $controller->getObjectManager()->expects($this->once())->method('flush');
        
        $this->dispatch('/user/settings', 'POST', array(
            'email' => 'foo@bar.de',
            'lastname' => 'test',
            'givenname' => 'huber',
            'gender' => 'm'
        ));
        $this->assertResponseStatusCode(200);
    }
    
    public function testSettingsWithInvalidPost(){
        $user = $this->getMock('User\Service\UserService');
        /* @var $controller \User\Controller\UserController */
        $controller = $this->getApplicationServiceLocator()->get('User\Controller\UserController');
        $controller->getUserManager()
            ->expects($this->once())
            ->method('getUserFromAuthenticator')
            ->will($this->returnValue($user));
        
        $this->dispatch('/user/settings', 'POST', array(
        ));
        $this->assertResponseStatusCode(200);
    }
}
