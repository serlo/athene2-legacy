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
namespace UserTest\Authentication;

use User\Authentication\Adapter\UserAuthAdapter;

class UserAuthAdapterTest extends \PHPUnit_Framework_TestCase
{

    protected $adapter;

    public function setUp()
    {
        $this->adapter = new UserAuthAdapter();
        $this->adapter->setIdentity('foo');
        $this->adapter->setPassword('bar');
        
        $this->adapter->setHashService($this->getMock('User\Authentication\HashService'));
        $this->adapter->setUserManager($this->getMock('User\Manager\UserManager'));
    }

    public function testAuthenticateUserNotFound()
    {
        $this->adapter->getUserManager()
            ->expects($this->once())
            ->method('findUserByEmail')
            ->will($this->throwException(new \User\Exception\UserNotFoundException()));
        
        $result = $this->adapter->authenticate();
        $this->assertFalse($result->isValid());
    }

    public function testAuthenticateUserTrashed()
    {
        $user = $this->getMock('User\Service\UserService');
        $user->expects($this->once())
            ->method('getPassword')
            ->will($this->returnValue('hash'));
        
        $user->expects($this->once())
            ->method('isTrashed')
            ->will($this->returnValue(true));
        
        $this->adapter->getUserManager()
            ->expects($this->once())
            ->method('findUserByEmail')
            ->will($this->returnValue($user));
        
        $this->adapter->getHashService()
            ->expects($this->once())
            ->method('hashPassword')
            ->will($this->returnValue('hash'));
        
        $result = $this->adapter->authenticate();
        $this->assertFalse($result->isValid());
    }

    public function testAuthenticateLoginMissing()
    {
        $user = $this->getMock('User\Service\UserService');
        $user->expects($this->once())
            ->method('getPassword')
            ->will($this->returnValue('hash'));
        
        $user->expects($this->once())
            ->method('isTrashed')
            ->will($this->returnValue(false));
        
        $user->expects($this->once())
            ->method('hasRole')
            ->with('login')
            ->will($this->returnValue(false));
        
        $this->adapter->getUserManager()
            ->expects($this->once())
            ->method('findUserByEmail')
            ->will($this->returnValue($user));
        
        $this->adapter->getHashService()
            ->expects($this->once())
            ->method('hashPassword')
            ->will($this->returnValue('hash'));
        
        $result = $this->adapter->authenticate();
        $this->assertFalse($result->isValid());
    }

    public function testAuthenticateHashMismatch()
    {
        $user = $this->getMock('User\Service\UserService');
        $user->expects($this->once())
            ->method('getPassword')
            ->will($this->returnValue('hash'));
        
        $this->adapter->getUserManager()
            ->expects($this->once())
            ->method('findUserByEmail')
            ->will($this->returnValue($user));
        
        $this->adapter->getHashService()
            ->expects($this->once())
            ->method('hashPassword')
            ->will($this->returnValue('another-hash'));
        
        $result = $this->adapter->authenticate();
        $this->assertFalse($result->isValid());
    }

    public function testAuthenticate()
    {
        $user = $this->getMock('User\Service\UserService');
        $user->expects($this->once())
            ->method('getPassword')
            ->will($this->returnValue('hash'));
        
        $user->expects($this->once())
            ->method('isTrashed')
            ->will($this->returnValue(false));
        
        $user->expects($this->once())
            ->method('hasRole')
            ->with('login')
            ->will($this->returnValue(true));
        
        $this->adapter->getUserManager()
            ->expects($this->once())
            ->method('findUserByEmail')
            ->will($this->returnValue($user));
        
        $this->adapter->getHashService()
            ->expects($this->once())
            ->method('hashPassword')
            ->will($this->returnValue('hash'));
        
        $result = $this->adapter->authenticate();
        $this->assertTrue($result->isValid());
    }
}