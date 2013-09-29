<?php
namespace AtheneTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

abstract class DefaultLayoutTestCase extends AbstractHttpControllerTestCase
{
    protected $traceError = true;
    
    public function setUp()
    {
        parent::setUp();
        $navigationProviderMock = $this->getMockBuilder('Taxonomy\Provider\NavigationProvider')
                     ->disableOriginalConstructor()
                     ->getMock();
        $subjectHydrator = $this->getMock('Subject\Hydrator\Navigation');
        $subjectHydrator->expects($this->once())->method('inject')->will($this->returnValue(array()));
        
        $this->getApplicationServiceLocator()->setAllowOverride(true);
        $this->getApplicationServiceLocator()->setService('Taxonomy\Provider\NavigationProvider', $navigationProviderMock);
        $this->getApplicationServiceLocator()->setService('Subject\Hydrator\Navigation', $subjectHydrator);
        $this->getApplicationServiceLocator()->setAllowOverride(false);
    }
}