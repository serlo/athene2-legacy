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
use Uuid\Entity\Uuid;
use AtheneTest\Bootstrap as AtheneBootstrap;
use AtheneTest\TestCase\ObjectManagerTestCase;

class UuidManagerTest extends ObjectManagerTestCase
{

    protected $uuidManager;

    public function setUp ()
    {
        parent::setUp();
        
        $sm = AtheneBootstrap::getServiceManager();
        $this->uuidManager = new UuidManager();
        
        $this->uuidManager->setClassResolver($sm->get('ClassResolver\ClassResolver'));
        $this->uuidManager->setObjectManager($sm->get('doctrine.entitymanager.orm_default'));
        $this->uuidManager->setServiceLocator($sm);
    }

    public function testGet ()
    {
        $this->uuidManager->getObjectManager()
            ->expects($this->once())
            ->method('find')
            ->will($this->returnValue((new Uuid())->setId(1)));
        
        $entity = $this->uuidManager->get(1);
        $this->assertNotNull($entity);
        $this->assertEquals(1, $entity->getId());
    }

    public function testCreate ()
    {
        $uuid = $this->uuidManager->create();
        $this->assertNotNull($uuid);
        $this->assertInstanceOf('\Uuid\Entity\UuidInterface', $uuid);
    }
}