<?php
namespace AtheneTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Ui\Listener\AcListener;
use AtheneTest\Bootstrap;

abstract class Athene2ApplicationTestCase extends AbstractHttpControllerTestCase
{

    protected $traceError = true;

    public function setUp()
    {
        $this->setApplicationConfig(include Bootstrap::findParentPath('config/application.testing.config.php'));
        parent::setUp();
        $this->setUpLayout();
        $this->setUpLanguage();
        $this->setUpAlias();
        $this->setUpContexter();
    }

    protected function setUpContexter()
    {
        $contexterMock = $this->getMock('Contexter\Contexter');
        $routerMock = $this->getMock('Contexter\Router\Router');
        $routerMock->expects($this->any())
            ->method('match')
            ->will($this->returnValue(array()));
        $this->getApplicationServiceLocator()->setAllowOverride(true);
        $this->getApplicationServiceLocator()->setService('Contexter\Contexter', $contexterMock);
        $this->getApplicationServiceLocator()->setService('Contexter\Router\Router', $routerMock);
        $this->getApplicationServiceLocator()->setAllowOverride(false);
    }

    protected function detachAggregatedListener($listener)
    {
        $this->getApplication()
            ->getEventManager()
            ->getSharedManager()
            ->detachAggregate($this->getApplicationServiceLocator()
            ->get($listener));
    }

    protected function setUpAlias()
    {
        $aliasManagerMock = $this->getMock('Alias\AliasManager');
        
        $this->getApplicationServiceLocator()->setAllowOverride(true);
        $this->getApplicationServiceLocator()->setService('Alias\AliasManager', $aliasManagerMock);
        $this->getApplicationServiceLocator()->setAllowOverride(false);
        return $this;
    }

    public function setUpLanguage()
    {
        $languageManagerMock = $this->getMock('Language\Manager\LanguageManager');
        $languageServiceMock = $this->getMock('Language\Service\LanguageService');
        
        $languageManagerMock->expects($this->atLeastOnce())
            ->method('getLanguageFromRequest')
            ->will($this->returnValue($languageServiceMock));
        $languageServiceMock->expects($this->atLeastOnce())
            ->method('getCode')
            ->will($this->returnValue('de'));
        
        $this->getApplicationServiceLocator()->setAllowOverride(true);
        $this->getApplicationServiceLocator()->setService('Language\Manager\LanguageManager', $languageManagerMock);
        $this->getApplicationServiceLocator()->setAllowOverride(false);
    }

    protected function setUpLayout()
    {
        $navigationProviderMock = $this->getMockBuilder('Taxonomy\Provider\NavigationProvider')
            ->disableOriginalConstructor()
            ->getMock();
        $subjectHydrator = $this->getMock('Subject\Hydrator\Navigation');
        $subjectHydrator->expects($this->any())
            ->method('hydrateConfig')
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
        $hrbac = $this->getMockBuilder('Common\Firewall\HydratableController')
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
                'HydratableController',
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