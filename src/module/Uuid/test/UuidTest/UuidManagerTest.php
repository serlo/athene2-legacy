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
namespace UuidTest;

use Uuid\Manager\UuidManager;
use ClassResolver\ClassResolver;
use AtheneTest\TestCase\ManagerTest;
use Uuid\Entity\Uuid;

/**
 * @codeCoverageIgnore
 */
class UuidManagerTest extends ManagerTest
{

    protected $uuidManager;

    public function setUp()
    {
        $this->uuidManager = new UuidManager();
        $this->setManager($this->uuidManager);
        
        $this->prepareClassResolver([
            'Uuid\Entity\UuidInterface' => 'Uuid\Entity\Uuid'
        ]);
    }

    protected function prepareEntity($id)
    {
        $mock = $this->getMock('Uuid\Entity\Uuid');
        $mock->expects($this->any())
            ->method('getId')
            ->will($this->returnValue($id));
        return $mock;
    }

    public function testInjectUuid()
    {
        $this->prepareObjectManager();
        
        $entity = $this->getMock('Uuid\Entity\UuidHolder');
        
        $this->uuidManager->getObjectManager()
            ->expects($this->once())
            ->method('persist');
        $this->uuidManager->getObjectManager()
            ->expects($this->once())
            ->method('flush');
        $entity->expects($this->once())
            ->method('setUuid');
        
        $this->uuidManager->injectUuid($entity);
    }

    public function testCreateUuid()
    {
        $this->prepareObjectManager();
        
        $this->uuidManager->getObjectManager()
            ->expects($this->once())
            ->method('persist');
        
        $this->uuidManager->getObjectManager()
            ->expects($this->once())
            ->method('flush');
        
        $this->assertInstanceOf('Uuid\Entity\UuidInterface', $this->uuidManager->createUuid());
    }

    public function testGetUuid()
    {
        $uuid = new Uuid();
        
        $this->prepareFind('Uuid\Entity\Uuid', 1, $uuid);
        
        $this->assertSame($uuid, $this->uuidManager->getUuid(1));
    }

    /**
     * @expectedException \Uuid\Exception\NotFoundException
     */
    public function testGetUuidNotFoundException()
    {
        $this->prepareFind('Uuid\Entity\Uuid', 1, NULL);
        
        $this->uuidManager->getUuid(1);
    }

    public function testFindUuidByName()
    {
        $uuid = $this->prepareEntity(1);
        $this->prepareFindOneBy('Uuid\Entity\Uuid', [
            'uuid' => 'foobar'
        ], $uuid);
        $this->assertSame($uuid, $this->uuidManager->findUuidByName('foobar'));
    }

    /**
     * @expectedException \Uuid\Exception\InvalidArgumentException
     */
    public function testGetUuidInvalidArgumentException()
    {
        $this->uuidManager->getUuid('asdf23');
    }
}