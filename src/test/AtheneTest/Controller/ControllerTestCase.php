<?php
namespace AtheneTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use AtheneTest\Bootstrap;

abstract class ControllerTestCase extends AbstractHttpControllerTestCase
{

    protected $traceError = true;

    protected $problematicViewHelpers = [
        'notifications' => 'User\View\Helper\Notification',
        'contexter' => 'Contexter\View\Helper\Contexter'
    ];

    public function setUp()
    {
        $this->setApplicationConfig(include Bootstrap::findParentPath('config/application.testing.config.php'));
        
        parent::setUp();
        
        $this->disableDatabase();
        
        $this->prepareNavigation();
        $this->setUpAlias();
        $this->mockProblematicViewHelpers();
        $this->disableGuards();
        
        $this->detachAggregatedListeners(\Event\Module::$listeners);
        $this->detachAggregatedListeners(\User\Module::$listeners);
        $this->detachAggregatedListeners(\Mailman\Module::$listeners);
        $this->detachAggregatedListeners(\Metadata\Module::$listeners);
        $this->detachAggregatedListeners(\Alias\Module::$listeners);
    }

    public function dispatch($url, $method = null, $params = array())
    {
        parent::dispatch($url, $method, $params);
        
        $this->assertControllerClass($this->getTestingControllerName());
    }

    abstract protected function getTestingControllerName();

    protected function detachAggregatedListeners(array $listeners)
    {
        foreach ($listeners as $listener) {
            $this->detachAggregatedListener($listener);
        }
    }

    protected function getController()
    {
        return $this->getApplicationServiceLocator()->get($this->getTestingControllerName());
    }

    protected function setService($name, $instance)
    {
        $this->getApplicationServiceLocator()->setAllowOverride(true);
        $this->getApplicationServiceLocator()->setService($name, $instance);
        $this->getApplicationServiceLocator()->setAllowOverride(false);
    }

    protected function setViewHelper($name, $instance)
    {
        $viewHelperPluginManager = $this->getApplicationServiceLocator()->get('ViewHelperManager');
        $viewHelperPluginManager->setAllowOverride(true);
        $viewHelperPluginManager->setService($name, $instance);
        $viewHelperPluginManager->setAllowOverride(false);
    }

    protected function prepareLanguageFromRequest($id, $code)
    {
        $languageManagerMock = $this->getMock('Language\Manager\LanguageManager');
        $languageServiceMock = $this->getMock('Language\Service\LanguageService');
        
        $languageManagerMock->expects($this->atLeastOnce())
            ->method('getLanguageFromRequest')
            ->will($this->returnValue($languageServiceMock));
        
        $languageServiceMock->expects($this->any())
            ->method('getId')
            ->will($this->returnValue($id));
        
        $languageServiceMock->expects($this->any())
            ->method('getCode')
            ->will($this->returnValue($code));
        
        $this->setService('Language\Manager\LanguageManager', $languageManagerMock);
    }

    protected function detachAggregatedListener($listener)
    {
        $this->getApplication()
            ->getEventManager()
            ->getSharedManager()
            ->detachAggregate($this->getApplicationServiceLocator()
            ->get($listener));
    }

    private function disableDatabase()
    {
        $entityManager = $this->getMock('Doctrine\ORM\EntityManager', array(), array(), '', false);
        $this->setService('Doctrine\ORM\EntityManager', $entityManager);
        $this->setService('doctrine.entitymanager.orm_default', $entityManager);
    }

    private function mockProblematicViewHelpers()
    {
        foreach ($this->problematicViewHelpers as $name => $class) {
            $this->setViewHelper($name, $this->getMock($class));
        }
    }

    private function setUpAlias()
    {
        $aliasManagerMock = $this->getMock('Alias\AliasManager');
        $this->setService('Alias\AliasManager', $aliasManagerMock);
        return $this;
    }

    private function prepareNavigation()
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

    private function disableGuards()
    {
        /* @var \Zend\Mvc\Application $application */
        $application = $this->getApplication();
        $serviceManager = $application->getServiceManager();
        $eventManager = $application->getEventManager();
        
        /* @var \ZfcRbac\Guard\GuardInterface[]|array $guards */
        $guards = $serviceManager->get('ZfcRbac\Guards');
        
        // Unload listeners, if any
        foreach ($guards as $guard) {
            $eventManager->detachAggregate($guard);
        }
    }
}