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
            ->will($this->returnValue($termServiceMock));
        $controller->setSharedTaxonomyManager($sharedTaxonomyManagerMock);
    }

    public function testUpdateAction()
    {
        $this->dispatch('/taxonomy/update/1');
        $this->assertResponseStatusCode(200);
    }

    protected function mockEntityManager()
    {
        $entityManagerMock = $this->getMock('Doctrine\ORM\EntityManager');
        
        $this->getApplicationServiceLocator()->setAllowOverride(true);
        $this->getApplicationServiceLocator()->setService('Doctrine\ORM\EntityManager', $entityManagerMock);
        $this->getApplicationServiceLocator()->setAllowOverride(false);
    }
}