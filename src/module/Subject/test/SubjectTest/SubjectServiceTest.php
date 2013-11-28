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
namespace SubjectTest;

use Subject\Service;

class SubjectServiceTest extends \PHPUnit_Framework_TestCase
{

    protected $subjectService, $pluginManagerMock, $termTaxonomyMock, $termServiceMock;

    public function setUp()
    {
        parent::setUp();
        
        $this->pluginManagerMock = $this->getMock('Subject\Plugin\PluginManager');
        $this->termTaxonomyMock = $this->getMock('Taxonomy\Entity\TaxonomyTerm');
        $this->termServiceMock = $this->getMock('Taxonomy\Service\TermService');
        
        $this->subjectService = new Service\SubjectService();
        $this->subjectService->setPluginManager($this->pluginManagerMock);
        $this->subjectService->setTermService($this->termServiceMock);
        $this->subjectService->setEntity($this->termTaxonomyMock);
        $this->subjectService->setConfig(array(
            'name' => 'foobar',
            'language' => 'de',
            'plugins' => array(
                array(
                    'name' => 'fooplugin',
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
        $this->assertEquals(true, $this->subjectService->isPluginWhitelisted('fooplugin'));
    }

    public function testGetPluginOptions()
    {
        $this->assertEquals(array(
            'bar' => 'foo'
        ), $this->subjectService->getPluginOptions('fooplugin'));
    }

    public function testGetPluginByScope()
    {
        $this->assertEquals('someplugin', $this->subjectService->getPluginByScope('fooplugin'));
    }

    public function testGetPlugin()
    {
        $pluginFake = new Fake\PluginFake();
        
        $this->pluginManagerMock->expects($this->once())
            ->method('setSubjectService')
            ->with($this->subjectService);
        $this->pluginManagerMock->expects($this->once())
            ->method('setPluginIdentification')
            ->with('fooplugin', 'someplugin');
        $this->pluginManagerMock->expects($this->once())
            ->method('setPluginIdentification')
            ->with('fooplugin', 'someplugin');
        $this->pluginManagerMock->expects($this->once())->method('get')->with('someplugin')->will($this->returnValue($pluginFake));
        
        $this->assertSame($pluginFake, $this->subjectService->fooplugin());
    }
    
    public function testDelegation(){
        $this->termTaxonomyMock->expects($this->once())->method('getId');
        $this->termTaxonomyMock->expects($this->once())->method('getSlug');
        $this->termTaxonomyMock->expects($this->once())->method('getName');
        
        $this->subjectService->getId();
        $this->subjectService->getSlug();
        $this->subjectService->getName();
    }
}