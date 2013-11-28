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
namespace CommonTest;

use CommonTest\Fake\DelegatorCollectionFake;

class DelegatorCollectionTest extends \PHPUnit_Framework_TestCase
{

    protected $delegatorCollection, $collectionMock, $managerMock;

    public function setUp()
    {
        $this->collectionMock = $this->getMock('Doctrine\Common\Collections\ArrayCollection');
        $this->managerMock = $this->getMock('CommonTest\Fake\ManagerFake');
        $this->delegatorCollection = new DelegatorCollectionFake($this->collectionMock, $this->managerMock);
        
        $this->collectionMock->expects($this->any())
            ->method('getIterator')
            ->will($this->returnValue(new \ArrayIterator(array())));
    }

    public function testAdd()
    {
        $this->collectionMock->expects($this->once())
            ->method('add')
            ->with(1)
            ->will($this->returnValue(1));
        $this->delegatorCollection->add(1);
    }

    public function testClear()
    {
        $this->collectionMock->expects($this->once())
            ->method('clear');
        $this->delegatorCollection->clear();
    }

    public function testContains()
    {
        $this->collectionMock->expects($this->once())
            ->method('contains')
            ->with(1);
        $this->delegatorCollection->contains(1);
    }

    public function testIsEmpty()
    {
        $this->collectionMock->expects($this->once())
            ->method('isEmpty');
        $this->delegatorCollection->isEmpty();
    }

    public function testRemove()
    {
        $this->collectionMock->expects($this->once())
            ->method('remove')
            ->with(1);
        $this->delegatorCollection->remove(1);
    }

    public function testRemoveElement()
    {
        $this->collectionMock->expects($this->once())
            ->method('removeElement')
            ->with(1);
        $this->delegatorCollection->removeElement(1);
    }

    public function testContainsKey()
    {
        $this->collectionMock->expects($this->once())
            ->method('containsKey')
            ->with(1);
        $this->delegatorCollection->containsKey(1);
    }

    public function testGetKeys()
    {
        $this->collectionMock->expects($this->once())
            ->method('getKeys');
        $this->delegatorCollection->getKeys();
    }

    public function testGetValues()
    {
        $this->collectionMock->expects($this->atLeastOnce())
            ->method('getValues');
        $this->delegatorCollection->asEntity();
        $this->delegatorCollection->getValues();
        
        $this->managerMock->expects($this->any())
            ->method('get');
        $this->delegatorCollection->asService();
        $this->delegatorCollection->getValues();
    }
    
    public function testSet(){
        $this->collectionMock->expects($this->once())
            ->method('set')
            ->with(1,2);
        $this->delegatorCollection->set(1,2);        
    }
}