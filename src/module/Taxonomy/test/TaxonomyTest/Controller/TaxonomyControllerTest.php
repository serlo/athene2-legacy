<?php
namespace TaxonomyTest\Controller;

use AtheneTest\Bootstrap;
use AtheneTest\Controller\DefaultLayoutTestCase;

class TaxonomyControllerTest extends DefaultLayoutTestCase
{

    protected $traceError = true;

    public function setUp()
    {
        $this->setApplicationConfig(include Bootstrap::findParentPath('config/application.testing.config.php'));
        parent::setUp();
        
        $controller = $this->getApplicationServiceLocator()->get('Taxonomy\Controller\TaxonomyController');
        $sharedTaxonomyManagerMock = $this->getMock('Taxonomy\Manager\SharedTaxonomyManager');
        $termServiceMock = $this->getMock('Taxonomy\Service\TermService');
        
        $sharedTaxonomyManagerMock->expects($this->once())
            ->method('getTerm')
            ->with('43')
            ->will($this->returnValue($termServiceMock));
        $controller->setSharedTaxonomyManager($sharedTaxonomyManagerMock);
    }

    public function testUpdateAction()
    {
        $this->dispatch('/restricted//taxonomy/update/43');
        $this->assertResponseStatusCode(200);
    }
}