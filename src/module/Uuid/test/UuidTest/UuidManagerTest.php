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

use Uuid\Entity\Uuid;
use AtheneTest\Bootstrap as AtheneBootstrap;

class UuidManagerTest extends \PHPUnit_Framework_TestCase
{

    protected $uuidManager;

    public function setUp ()
    {
        $sm = AtheneBootstrap::getServiceManager();
        $this->uuidManager = $sm->get('Uuid\Manager\UuidManager');
    }

    public function testGet ()
    {        
        $this->assertEquals(1, $this->uuidManager->get(1)->getId());
    }

    public function testCreate ()
    {
        $uuid = $this->uuidManager->create();
        $this->assertNotNull($uuid);
        $this->assertNotNull($uuid->getId());
        $this->assertInstanceOf('\Uuid\Entity\UuidInterface', $uuid);
    }
}