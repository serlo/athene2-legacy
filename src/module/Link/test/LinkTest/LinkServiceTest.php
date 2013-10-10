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
namespace LinkTest;

use Link\Service\LinkService;

class LinkServiceTest extends \PHPUnit_Framework_TestCase
{

    protected $linkService, $entityMock;

    public function setUp()
    {
        $this->linkService = new LinkService();
        $this->entityMock = $this->getMock('LinkTest\Fake\LinkFake');
        $this->linkService->setEntity($this->entityMock);
        $this->linkService->setLinkManager($this->getMock('Link\Manager\LinkManager'));
        $this->linkService->getLinkManager()
            ->expects($this->any())
            ->method('getEntity')
            ->will($this->returnValue($this->getMock('LinkTest\Fake\LinkTypeFake')));
    }

    public function testRemoveChild()
    {
        $this->entityMock->expects($this->once())
            ->method('removeChild');
        $this->linkService->removeChild($this->getMock('LinkTest\Fake\LinkFake'));
    }

    public function testRemoveParent()
    {
        $this->entityMock->expects($this->once())
            ->method('removeParent');
        $this->linkService->removeParent($this->getMock('LinkTest\Fake\LinkFake'));
    }

    public function testGetChildren()
    {
        $this->entityMock->expects($this->once())
            ->method('getChildren');
        $this->linkService->getChildren();
    }

    public function testGetParents()
    {
        $this->entityMock->expects($this->once())
            ->method('getParents');
        $this->linkService->getParents();
    }

    public function testAddParent()
    {
        $this->entityMock->expects($this->once())
            ->method('addParent');
        $this->linkService->addParent($this->getMock('LinkTest\Fake\LinkFake'));
    }

    public function testAddChild()
    {
        $this->entityMock->expects($this->once())
            ->method('addChild');
        $this->linkService->addChild($this->getMock('LinkTest\Fake\LinkFake'));
    }
}