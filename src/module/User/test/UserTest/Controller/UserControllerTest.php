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
        $controller->setRegisterForm($this->registerForm);
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

    public function testLoginActionFailForm()
    {
        $this->dispatch('/user/login', 'POST', array(
            'test' => 'user',
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
            'email' => 'fa98s',
            
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
        $controller->setRegisterForm($registerFormMock);
        
        $this->dispatch('/user/register', 'POST', $data);
        $this->assertResponseStatusCode(302);
    }
}