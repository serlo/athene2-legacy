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
use User\Entity\Role;

/**
 * @codeCoverageIgnore
 */
class RoleTest extends Model
{

    /**
     *
     * @return Role
     */
    public function getObject()
    {
        return parent::getObject();
    }

    public function setUp()
    {
        $this->setObject(new Role());
    }

    protected function getData()
    {
        return array(
            'id' => NULL,
            'name' => 'name',
            'description' => 'desc'
        );
    }

    public function testAddUser()
    {
        $user = $this->getMock('User\Entity\User');
        $this->getObject()->addUser($user);
        $this->assertSame($user, $this->getObject()
            ->getUsers()
            ->first());
    }

    public function testRemoveUser()
    {
        $user = $this->getMock('User\Entity\User');
        $this->getObject()->addUser($user);
        $this->getObject()->removeUser($user);
        $this->assertEquals(0, $this->getObject()
            ->getUsers()
            ->count());
    }
}