<?php
namespace AtheneTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
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
        $this->setUpNotification();
        $this->setUpFirewall();
        
        $this->detachAggregatedListeners(\Event\Module::$listeners);
        $this->detachAggregatedListeners(\User\Module::$listeners);
        $this->detachAggregatedListeners(\Mailman\Module::$listeners);
        $this->detachAggregatedListeners(\Metadata\Module::$listeners);
    }

    protected function detachAggregatedListeners(array $listeners)
    {
        foreach ($listeners as $listener) {
            $this->detachAggregatedListener($listener);
        }
    }

    protected function setUpNotification()
    {
        $viewHelperPluginManager = $this->getApplicationServiceLocator()->get('ViewHelperManager');
        $viewHelperPluginManager->setAllowOverride(true);
        $viewHelperPluginManager->setService('notifications', $this->getMock('User\View\Helper\Notification'));
        $viewHelperPluginManager->setAllowOverride(false);
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
        
        $this->getApplicationServiceLocator()->setAllowOverride(true);
        $this->getApplicationServiceLocator()->setService('Taxonomy\Provider\NavigationProvider', $navigationProviderMock);
        $this->getApplicationServiceLocator()->setService('Subject\Hydrator\Navigation', $subjectHydrator);
        $this->getApplicationServiceLocator()->setAllowOverride(false);
    }

    protected function setUpFirewall()
    {
        /* @var \Zend\Mvc\Application $application */
        $application = $this->getApplication();
        $serviceManager = $application->getServiceManager();
        $eventManager = $application->getEventManager();
        
        /* @var \ZfcRbac\Guard\GuardInterface[]|array $guards */
        $guards = $serviceManager->get('ZfcRbac\Guards');
        
        // Register listeners, if any
        foreach ($guards as $guard) {
            $eventManager->detachAggregate($guard);
        }
    }
}