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
use User\Entity\Notification;

/**
 * @codeCoverageIgnore
 */
class NotificationTest extends Model
{

    /**
     *
     * @return Notification
     */
    public function getObject()
    {
        return parent::getObject();
    }

    public function setUp()
    {
        $this->setObject(new Notification());
    }

    protected function getData()
    {
        $user = $this->getMock('User\Entity\User');
        $user->expects($this->any())
            ->method('getToken')
            ->will($this->returnValue('abcd'));
        return array(
            'id' => NULL,
            'user' => $user,
            'seen' => true,
            'date' => new \Datetime('now')
        );
    }

    public function testAddEvent()
    {
        $this->assertSame($this->getObject(), $this->getObject()
            ->addEvent($this->getMock('User\Entity\NotificationEvent')));
    }

    public function testGetActors()
    {
        $mock = $this->getMock('User\Entity\NotificationEvent');
        $mock->expects($this->atLeastOnce())
            ->method('getActor')
            ->will($this->returnValue('foo'));
        $this->getObject()->addEvent($mock);
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $this->getObject()
            ->getActors());
        $this->assertEquals('foo', $this->getObject()
            ->getActors()
            ->first());
    }

    public function testGetReferences()
    {
        $mock = $this->getMock('User\Entity\NotificationEvent');
        $mock->expects($this->atLeastOnce())
            ->method('getReference')
            ->will($this->returnValue('foo'));
        $this->getObject()->addEvent($mock);
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $this->getObject()
            ->getReferences());
        $this->assertEquals('foo', $this->getObject()
            ->getReferences()
            ->first());
    }

    public function testGetObjects()
    {
        $mock = $this->getMock('User\Entity\NotificationEvent');
        $mock->expects($this->atLeastOnce())
            ->method('getObject')
            ->will($this->returnValue('foo'));
        $this->getObject()->addEvent($mock);
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $this->getObject()
            ->getObjects());
        $this->assertEquals('foo', $this->getObject()
            ->getObjects()
            ->first());
    }

    public function testGetEventName()
    {
        $mock = $this->getMock('User\Entity\NotificationEvent');
        $event = $this->getMock('Event\Entity\Event');
        $mock->expects($this->atLeastOnce())
            ->method('getEvent')
            ->will($this->returnValue($event));
        $event->expects($this->atLeastOnce())
            ->method('getName')
            ->will($this->returnValue('foo'));
        $this->getObject()->addEvent($mock);
        
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $this->getObject()
            ->getActors());
        $this->assertEquals('foo', $this->getObject()
            ->getEventName());
    }
}