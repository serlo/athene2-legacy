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

use Uuid\Router\UuidRouter;

class UuidRouterTest extends \PHPUnit_Framework_TestCase
{

    protected $uuidRouter;

    public function setUp()
    {
        $this->uuidRouter = new UuidRouter();
        
        $this->uuidRouter->setUuidManager($this->getMock('Uuid\Manager\UuidManager'));
    }

    public function testAssemble()
    {
        $entity = $this->getMock('Uuid\Entity\Uuid');
        $um = $this->uuidRouter->getUuidManager()
            ->expects($this->once())
            ->method('getUuid')
            ->will($this->returnValue($entity));
        
        $entity->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(1));
        
        $entity->expects($this->once())
            ->method('is')
            ->will($this->returnValue(true));
        
        $this->uuidRouter->setConfig(array(
            'routes' => array(
                'foo' => 'bar/%s'
            )
        ));
        
        $this->assertEquals('bar/1', $this->uuidRouter->assemble(1));
    }

    /**
     * @expectedException \Uuid\Router\Exception\MatchingException
     */
    public function testAssembleMatchingException()
    {
        $entity = $this->getMock('Uuid\Entity\Uuid');
        $um = $this->uuidRouter->getUuidManager()
            ->expects($this->once())
            ->method('getUuid')
            ->will($this->returnValue($entity));
        
        $entity->expects($this->atLeastOnce())
            ->method('is')
            ->will($this->returnValue(false));
        
        $this->uuidRouter->setConfig(array(
            'routes' => array(
                'foo' => 'bar/%s'
            )
        ));
        
        $this->uuidRouter->assemble(1);
    }
}