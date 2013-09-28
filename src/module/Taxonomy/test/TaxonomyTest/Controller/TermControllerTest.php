<?php
namespace TaxonomyTest\Controller;

use AtheneTest\Controller\DefaultLayoutTestCase;
use AtheneTest\Bootstrap;

class TermControllerTest extends DefaultLayoutTestCase
{

    protected $traceError = true;
    
    protected $termServiceMock;

    public function setUp()
    {
        $this->setApplicationConfig(include Bootstrap::findParentPath('config/application.testing.config.php'));
        parent::setUp();
        
        $controller = $this->getApplicationServiceLocator()->get('Taxonomy\Controller\TermController');
        $sharedTaxonomyManagerMock = $this->getMock('Taxonomy\Manager\SharedTaxonomyManager');
        $this->termServiceMock = $this->getMock('Taxonomy\Service\TermService');
        
        $sharedTaxonomyManagerMock->expects($this->once())
            ->method('getTerm')
            ->will($this->returnValue($this->termServiceMock));
        
        $controller->setSharedTaxonomyManager($sharedTaxonomyManagerMock);
    }

    public function testUpdateAction()
    {
        $this->termServiceMock->expects($this->once())
            ->method('update');
        
        $this->dispatch('/taxonomy/update/1');
        $this->assertResponseStatusCode(200);
    }
}