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

use Link\Manager\LinkManager;
use LinkTest\Fake\LinkFake;

class LinkManagerTest extends \PHPUnit_Framework_TestCase
{

    protected $linkManager, $classResolverMock, $serviceLocatorMock, $linkServiceMock;

    public function setUp()
    {
        parent::setUp();
        
        $this->linkManager = new LinkManager();
        $this->classResolverMock = $this->getMock('ClassResolver\ClassResolver');
        $this->serviceLocatorMock = $this->getMock('Zend\ServiceManager\ServiceManager');
        $this->linkServiceMock = $this->getMock('Link\Service\LinkService');
        
        $this->linkServiceMock->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(1));
        
        $this->linkManager->setClassResolver($this->classResolverMock);
        $this->linkManager->setServiceLocator($this->serviceLocatorMock);
    }

    public function testGetLink()
    {
        $linkEntity = new LinkFake();
        $linkEntity->setId(1);
        
        $this->serviceLocatorMock->expects($this->once())
            ->method('get')
            ->will($this->returnValue($this->linkServiceMock));
        
        $this->classResolverMock->expects($this->once())
            ->method('resolveClassName')
            ->will($this->returnValue('Link\Service\LinkService'));
        
        $this->assertEquals($this->linkServiceMock, $this->linkManager->getLink($linkEntity));
    }
}