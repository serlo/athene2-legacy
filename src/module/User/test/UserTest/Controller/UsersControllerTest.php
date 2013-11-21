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

class UsersControllerTest extends Athene2ApplicationTestCase
{

    protected $controller;

    public function setUp()
    {
        parent::setUp();
        $userManagerMock = $this->getMock('User\Manager\UserManager');
        
        $this->controller = $this->getApplicationServiceLocator()->get('User\Controller\UsersController');
        $this->controller->setUserManager($userManagerMock);
    }

    public function testUsersAction()
    {
        $this->controller->getUserManager()
            ->expects($this->once())
            ->method('findAllUsers')
            ->will($this->returnValue(array()));
        $this->dispatch('/users');
        $this->assertResponseStatusCode(200);
    }

    public function testRolesAction()
    {
        $this->controller->getUserManager()
            ->expects($this->once())
            ->method('findAllRoles')
            ->will($this->returnValue(array()));
        $this->dispatch('/users/roles');
        $this->assertResponseStatusCode(200);
    }

    public function testRoleAction()
    {
        $role = $this->getMock('User\Entity\Role');
        $this->controller->getUserManager()
            ->expects($this->once())
            ->method('findRole')
            ->will($this->returnValue($role));
        $role->expects($this->once())
            ->method('getUsers')
            ->will($this->returnValue(array()));
        $this->dispatch('/users/role/1');
        $this->assertResponseStatusCode(200);
    }
}