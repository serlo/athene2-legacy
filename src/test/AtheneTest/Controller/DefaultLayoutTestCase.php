<?php
namespace AtheneTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

abstract class DefaultLayoutTestCase extends AbstractHttpControllerTestCase
{

    public function setUp()
    {
        parent::setUp();
        $navigationProviderMock = $this->getMockBuilder('Taxonomy\Provider\NavigationProvider')
                     ->disableOriginalConstructor()
                     ->getMock();
        $this->getApplicationServiceLocator()->setAllowOverride(true);
        $this->getApplicationServiceLocator()->setService('Taxonomy\Provider\NavigationProvider', $navigationProviderMock);
        $this->getApplicationServiceLocator()->setAllowOverride(false);
    }
}