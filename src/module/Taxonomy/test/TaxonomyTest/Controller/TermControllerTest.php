<?php
namespace TaxonomyTest\Controller;

use AtheneTest\Controller\DefaultLayoutTestCase;
use AtheneTest\Bootstrap;

class TermControllerTest extends DefaultLayoutTestCase
{

    protected $traceError = true;

    protected $termServiceMock, $objectManagerMock;

    public function setUp()
    {
        $this->setApplicationConfig(include Bootstrap::findParentPath('config/application.testing.config.php'));
        parent::setUp();
        $this->setUpFirewall();
        
        $controller = $this->getApplicationServiceLocator()->get('Taxonomy\Controller\TermController');
        $this->sharedTaxonomyManagerMock = $this->getMock('Taxonomy\Manager\SharedTaxonomyManager');
        $this->objectManagerMock = $this->getMock('Doctrine\ORM\EntityManager', array(), array(), '', false);
        $this->termServiceMock = $this->getMock('Taxonomy\Service\TermService');
        $languageManagerMock = $this->getMock('Language\Manager\LanguageManager');
        
        $this->sharedTaxonomyManagerMock->expects($this->any())
            ->method('getObjectManager')
            ->will($this->returnValue($this->objectManagerMock));
        
        $controller->setSharedTaxonomyManager($this->sharedTaxonomyManagerMock);
        $controller->setLanguageManager($languageManagerMock);
    }

    public function testCreateActionForm()
    {
        $this->dispatch('/taxonomy/term/create/1/5');
        $this->assertResponseStatusCode(200);
    }

    public function testCreateAction()
    {
        $this->objectManagerMock->expects($this->once())
            ->method('flush');
        $this->sharedTaxonomyManagerMock->expects($this->once())
            ->method('createTerm');
        
        
        $this->dispatch('/taxonomy/term/create/1/5', 'POST', array(
            'id' => 5,
            'taxonomy' => 2,
            'term' => array(
                'name' => "asdf"
            ),
            'parent' => 1
        ));
        
        $this->assertResponseStatusCode(302);
    }

    public function testUpdateActionForm()
    {
        $this->sharedTaxonomyManagerMock->expects($this->once())
            ->method('getTerm')
            ->with('1')
            ->will($this->returnValue($this->termServiceMock));
        
        $this->termServiceMock->expects($this->once())
            ->method('getArrayCopy')
            ->will($this->returnValue(array(
            'id' => 5,
            'term' => array(
                'name' => "asdf"
            ),
            'taxonomy' => 2,
            'parent' => 1
        )));
        
        $this->dispatch('/taxonomy/term/update/1');
        $this->assertResponseStatusCode(200);
    }

    public function testUpdateAction()
    {
        $this->sharedTaxonomyManagerMock->expects($this->once())
            ->method('getTerm')
            ->with('1')
            ->will($this->returnValue($this->termServiceMock));
        
        $this->termServiceMock->expects($this->once())
            ->method('getArrayCopy')
            ->will($this->returnValue(array(
            'id' => 5,
            'term' => array(
                'name' => "asdf"
            ),
            'taxonomy' => 2,
            'parent' => 1
        )));
        
        $this->objectManagerMock->expects($this->once())
            ->method('flush');
        $this->sharedTaxonomyManagerMock->expects($this->once())
            ->method('updateTerm');
        
        $this->dispatch('/taxonomy/term/update/1', 'POST', array(
            'id' => 5,
            'taxonomy' => 2,
            'term' => array(
                'name' => "asdf"
            ),
            'parent' => 1
        ));
        $this->assertResponseStatusCode(302);
    }
}