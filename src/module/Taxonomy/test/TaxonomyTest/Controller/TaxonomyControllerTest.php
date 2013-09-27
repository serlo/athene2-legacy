<?php
namespace TaxonomyTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use AtheneTest\Bootstrap;

class TaxonomyControllerTest extends AbstractHttpControllerTestCase
{
    protected $traceError = true;

    public function setUp()
    {
        $this->setApplicationConfig(include Bootstrap::findParentPath('config/testing.config.php'));
        parent::setUp();
    }

    public function testUpdateAction()
    {
        $this->dispatch('/taxonomy/update/43');
        $this->assertResponseStatusCode(200);
    }
}  