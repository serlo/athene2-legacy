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
namespace LearningResourceTest\Plugin;

use LearningResource\Plugin\Link\LinkPlugin;

class LinkPluginTest extends \PHPUnit_Framework_TestCase
{

    protected $linkPlugin;

    protected $entityServiceMock, $sharedLinkManagerMock, $linkManagerMock, $linkServiceMock, $entityManagerMock, $linkPluginMock;

    public function setUp()
    {
        $this->linkPlugin = new LinkPlugin();
        
        $this->entityServiceMock = $this->getMock('Entity\Service\EntityService', array(
            'getType',
            'link',
            'isPluginWhitelisted',
            'getEntity'
        ));
        $this->entityTypeMock = $this->getMock('Entity\Entity\Type');
        $this->sharedLinkManagerMock = $this->getMock('Link\Manager\SharedLinkManager');
        $this->linkManagerMock = $this->getMock('Link\Manager\LinkManager');
        $this->linkServiceMock = $this->getMock('Link\Service\LinkService');
        $this->entityManagerMock = $this->getMock('Entity\Manager\EntityManager');
        $this->entityMock = $this->getMock('Entity\Entity\Entity');
        
        $this->linkPlugin->setEntityService($this->entityServiceMock);
        $this->linkPlugin->setSharedLinkManager($this->sharedLinkManagerMock);
        $this->linkPlugin->setEntityManager($this->entityManagerMock);
        
        $this->sharedLinkManagerMock->expects($this->any())
            ->method('findLinkManagerByName')
            ->with('foobar', 'Entity\Entity\EntityLinkType')
            ->will($this->returnValue($this->linkManagerMock));
        
        $this->entityServiceMock->expects($this->any())->method('getEntity')->will($this->returnValue($this->entityMock));
        
        $this->linkManagerMock->expects($this->any())
            ->method('getLink')
            ->will($this->returnValue($this->linkServiceMock));
        
        $this->entityServiceMock->expects($this->any())
            ->method('getType')
            ->will($this->returnValue($this->entityTypeMock));
    }

    private function setUpManyToMany()
    {
        $this->linkPlugin->setConfig(array(
            'types' => array(
                array(
                    'to' => 'testbar',
                    'reversed_by' => 'link'
                )
            ),
            'type' => 'foobar',
            'association' => 'many-to-many'
        ));
        
        $this->entityTypeMock->expects($this->atLeastOnce())
            ->method('getName')
            ->will($this->returnValue('testbar'));
    }

    private function setUpOneToOne()
    {
        $this->linkPlugin->setConfig(array(
            'types' => array(
                array(
                    'to' => 'testbar',
                    'reversed_by' => 'link'
                )
            ),
            'type' => 'foobar',
            'association' => 'one-to-one'
        ));
        
        $this->linkPluginMock = $this->getMock('LearningResource\Plugin\Link\LinkPlugin');
        
        $this->entityServiceMock->expects($this->atLeastOnce())
            ->method('isPluginWhitelisted')
            ->with('link')
            ->will($this->returnValue(true));
        
        $this->entityServiceMock->expects($this->atLeastOnce())
            ->method('link')
            ->will($this->returnValue($this->linkPluginMock));
        
        $this->linkPluginMock->expects($this->atLeastOnce())
            ->method('hasParent')
            ->will($this->returnValue(false));
        $this->linkPluginMock->expects($this->atLeastOnce())
            ->method('hasChild')
            ->will($this->returnValue(false));
        
        $this->entityTypeMock->expects($this->atLeastOnce())
            ->method('getName')
            ->will($this->returnValue('testbar'));
    }

    public function testAddParent()
    {
        $this->setUpOneToOne();
        
        $this->entityTypeMock->expects($this->atLeastOnce())
            ->method('getName')
            ->will($this->returnValue('testbar'));
        
        $this->linkServiceMock->expects($this->atLeastOnce())
            ->method('addParent');
        
        $this->linkPlugin->addParent($this->entityServiceMock);
        
        $this->setUpManyToMany();
        
        $this->linkPlugin->addParent($this->entityServiceMock);
    }

    public function testAddChild()
    {
        $this->setUpOneToOne();
        
        $this->entityTypeMock->expects($this->atLeastOnce())
            ->method('getName')
            ->will($this->returnValue('testbar'));
        
        $this->linkServiceMock->expects($this->atLeastOnce())
            ->method('addChild');
        
        $this->linkPlugin->addChild($this->entityServiceMock);
        
        $this->setUpManyToMany();
        
        $this->linkPlugin->addChild($this->entityServiceMock);
    }
}