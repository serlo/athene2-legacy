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
namespace EntityTest;

use Entity\Service;

class EntityServiceTest extends \PHPUnit_Framework_TestCase
{

    protected $entityService, $pluginManagerMock, $entityMock, $sharedTaxonomyManagerMock;

    public function setUp()
    {
        parent::setUp();
        
        $this->pluginManagerMock = $this->getMock('Entity\Plugin\PluginManager');
        $this->entityMock = $this->getMock('Entity\Entity\Entity');
        $this->sharedTaxonomyManagerMock = $this->getMock('Taxonomy\Manager\SharedTaxonomyManager');
        
        $this->entityService = new Service\EntityService();
        $this->entityService->setPluginManager($this->pluginManagerMock);
        $this->entityService->setEntity($this->entityMock);
        $this->entityService->setTaxonomyManager($this->sharedTaxonomyManagerMock);
        $this->entityService->setConfig(array(
            'plugins' => array(
                'fooplugin' => array(
                    'plugin' => 'someplugin',
                    'options' => array(
                        'bar' => 'foo'
                    )
                )
            )
        ));
    }

    public function testIsPluginWhitelisted()
    {
        $this->assertEquals(true, $this->entityService->isPluginWhitelisted('fooplugin'));
    }

    public function testGetPluginOptions()
    {
        $this->assertEquals(array(
            'bar' => 'foo'
        ), $this->entityService->getPluginOptions('fooplugin'));
    }

    public function testGetScopesForPlugin()
    {
        $this->assertEquals(array('fooplugin'), $this->entityService->getScopesForPlugin('someplugin'));
    }

    public function testGetPlugin()
    {
        $pluginFake = new Fake\PluginFake();
        
        $this->pluginManagerMock->expects($this->once())
            ->method('setEntityService')
            ->with($this->entityService);
        $this->pluginManagerMock->expects($this->once())
            ->method('get')
            ->with('someplugin')
            ->will($this->returnValue($pluginFake));
        
        $this->assertSame($pluginFake, $this->entityService->fooplugin());
    }

    public function testDelegation()
    {
        $this->entityMock->expects($this->once())
            ->method('getId');
        
        $this->entityService->getId();
    }
}