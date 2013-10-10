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
        $this->setUpLayout();
    }

    protected function setUpLayout()
    {
        $navigationProviderMock = $this->getMockBuilder('Taxonomy\Provider\NavigationProvider')
            ->disableOriginalConstructor()
            ->getMock();
        $subjectHydrator = $this->getMock('Subject\Hydrator\Navigation');
        $subjectHydrator->expects($this->once())
            ->method('inject')
            ->will($this->returnValue(array()));
        
        $rbacServiceMock = $this->getMock('ZfcRbac\Service\Rbac', array(
            'isGranted'
        ));
        
        $rbacServiceMock->expects($this->any())
            ->method('isGranted')
            ->will($this->returnValue(true));
        
        AcListener::setRbacService($rbacServiceMock);
        
        $this->getApplicationServiceLocator()->setAllowOverride(true);
        $this->getApplicationServiceLocator()->setService('Taxonomy\Provider\NavigationProvider', $navigationProviderMock);
        $this->getApplicationServiceLocator()->setService('Subject\Hydrator\Navigation', $subjectHydrator);
        $this->getApplicationServiceLocator()->setAllowOverride(false);
    }

    protected function setUpFirewall($role = NULL, $allow = true)
    {
        $rbacService = $this->getMockBuilder('ZfcRbac\Service\Rbac')
            ->disableOriginalConstructor()
            ->getMock();
        $rbac = $this->getMockBuilder('ZfcRbac\Firewall\Controller')
            ->disableOriginalConstructor()
            ->getMock();
        
        $rbacService->expects($this->atLeastOnce())
            ->method('getFirewall')
            ->will($this->returnValueMap(array(
            array(
                'controller',
                $rbac
            ),
            array(
                'route',
                $rbac
            )
        )));
        
        if ($role) {
            $rbac->expects($this->atLeastOnce())
                ->method('isGranted')
                ->with($role)
                ->will($this->returnValue($allow));
        } else {
            $rbac->expects($this->atLeastOnce())
                ->method('isGranted')
                ->will($this->returnValue($allow));
        }
        $this->getApplicationServiceLocator()->setAllowOverride(true);
        $this->getApplicationServiceLocator()->setService('ZfcRbac\Service\Rbac', $rbacService);
        $this->getApplicationServiceLocator()->setAllowOverride(false);
    }
}