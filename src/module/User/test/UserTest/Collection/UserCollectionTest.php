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
namespace UserTest\Collection;

use User\Collection\UserCollection;

class UserCollectionTest extends \PHPUnit_Framework_TestCase
{

    private $_coll, $user;

    public function setUp()
    {
        $collection = $this->getMock('Doctrine\Common\Collections\ArrayCollection');
        $manager = $this->getMock('User\Manager\UserManager');
        $this->user = $this->getMock('User\Entity\User');
        $this->user->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(1));
        $this->_coll = new UserCollection($collection, $manager);
    }

    /**
     * @expectedException \User\Exception\InvalidArgumentException
     */
    public function testManagerInvalidArgumentException()
    {
        $collection = $this->getMock('Doctrine\Common\Collections\ArrayCollection');
        new UserCollection($collection, $this->getMock('User\Entity\User'));
    }

    public function testGetFromManager()
    {
        $this->_coll->getManager()
            ->expects($this->once())
            ->method('getUser')
            ->with(1)
            ->will($this->returnValue($this->getMock('User\Service\UserService')));
        $this->assertInstanceOf('User\Service\UserService', $this->_coll->getFromManager($this->user));
    }
}