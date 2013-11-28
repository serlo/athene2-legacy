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
namespace UserTest\Entity;

use AtheneTest\TestCase\Model;
use Uuid\Entity\Uuid;
use User\Entity\User;

/**
 * @codeCoverageIgnore
 */
class UserTest extends Model
{

    /**
     * (non-PHPdoc)
     *
     * @see \AtheneTest\TestCase\Model::getObject()
     * @return User
     */
    public function getObject()
    {
        return parent::getObject();
    }

    public function setUp()
    {
        $this->setObject(new User());
    }

    protected function getData()
    {
        return array(
            'email' => 'asdf',
            'username' => 'asdf',
            'password' => '12345',
            'logins' => 10,
            'lastLogin' => 12345,
            'date' => 1234,
            'adsEnabled' => false
        );
    }

    public function testRoles()
    {
        $role = $this->getMock('User\Entity\Role');
        $role->expects($this->atLeastOnce())
            ->method('getId')
            ->will($this->returnValue(1));
        $role->expects($this->atLeastOnce())
            ->method('getName')
            ->will($this->returnValue('login'));
        
        $this->getObject()->addRole($role);
        $this->assertSame($role, $this->getObject()
            ->getRoles()
            ->first());
        $this->assertTrue($this->getObject()
            ->hasRole(1));
        $this->assertTrue($this->getObject()
            ->hasRole('login'));
        $this->assertFalse($this->getObject()
            ->hasRole('norole'));
        $this->assertEquals(array('login'), $this->getObject()
            ->getRoleNames());
        $this->getObject()->removeRole($role);
        $this->assertEquals(0, $this->getObject()
            ->getRoles()
            ->count());
    }
    
    public function testToken(){
        $this->assertGreaterThan(4, strlen($this->getObject()->getToken()));
    }

    public function testPopulate()
    {
        $this->getObject()->populate(array(
            'email' => 'asdf',
            'username' => 'asdf',
            'password' => '12345'
        ));
        $this->assertEquals('asdf', $this->getObject()
            ->getEmail());
    }
}