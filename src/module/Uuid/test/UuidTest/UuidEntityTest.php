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

use AtheneTest\TestCase\Model;

/**
 * @codeCoverageIgnore
 */
class UuidEntityTest extends Model
{

    public function setUp()
    {
        $entity = new \Uuid\Entity\UuidEntity();
        $entity->setUuid($this->getMock('Uuid\Entity\Uuid'));
        $this->setObject($entity);
    }
    
    /*
     * (non-PHPdoc) @see \AtheneTest\TestCase\Model::getData()
     */
    protected function getData()
    {
        return array();
    }

    public function testGetId()
    {
        $this->getObject()
            ->getUuidEntity()
            ->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(1));
        $this->assertEquals(1, $this->getObject()
            ->getId());
    }

    public function testGetUuid()
    {
        $this->getObject()
            ->getUuidEntity()
            ->expects($this->once())
            ->method('getUuid')
            ->will($this->returnValue(1));
        $this->assertEquals(1, $this->getObject()
            ->getUuid());
    }
    

    public function testTrahsed()
    {
        $this->getObject()
            ->getUuidEntity()
            ->expects($this->atLeastOnce())
            ->method('getTrashed')
            ->will($this->returnValue(1));
        $this->getObject()
            ->getUuidEntity()
            ->expects($this->once())
            ->method('setTrashed');
        
        $this->getObject()->setTrashed(true);
        $this->assertEquals(true, $this->getObject()
            ->isTrashed());
        $this->assertEquals(1, $this->getObject()
            ->getTrashed());
    }
}