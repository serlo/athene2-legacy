<?php
namespace AtheneTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Ui\Listener\AcListener;
use AtheneTest\Bootstrap;

abstract class DefaultLayoutTestCase extends AbstractHttpControllerTestCase
{

    protected $traceError = true;

    public function setUp()
    {
        $this->setApplicationConfig(include Bootstrap::findParentPath('config/application.testing.config.php'));
        parent::setUp();
        $navigationProviderMock = $this->getMockBuilder('Taxonomy\Provider\NavigationProvider')
            ->disableOriginalConstructor()
            ->getMock();
        $subjectHydrator = $this->getMock('Subject\Hydrator\Navigation');
        $subjectHydrator->expects($this->once())
            ->method('inject')
            ->will($this->returnValue(array()));
        
        $rbacServiceMock = $this->getMock('ZfcRbac\Service\Rbac', array('isGranted'));
        
        $rbacServiceMock->expects($this->any())
            ->method('isGranted')
            ->will($this->returnValue(true));
        
        AcListener::setRbacService($rbacServiceMock);
        
        $this->getApplicationServiceLocator()->setAllowOverride(true);
        $this->getApplicationServiceLocator()->setService('Taxonomy\Provider\NavigationProvider', $navigationProviderMock);
        $this->getApplicationServiceLocator()->setService('Subject\Hydrator\Navigation', $subjectHydrator);
        $this->getApplicationServiceLocator()->setAllowOverride(false);
    }
}