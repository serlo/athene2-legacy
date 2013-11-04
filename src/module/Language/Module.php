<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author	Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Language;

use Zend\Mvc\MvcEvent;
use Zend\Mvc\ModuleRouteListener;

class Module
{

    public function onBootstrap(MvcEvent $e)
    {
        $app = $e->getTarget();
        $serviceManager = $app->getServiceManager();
        
        // View Exception
        // $serviceManager->get('Zend\Mvc\View\Http\ExceptionStrategy')->attach($app->getEventManager(), 1);
        // $serviceManager->get('Zend\Mvc\View\Http\InjectViewModelListener')->attach($app->getEventManager(), -100);
        
        // Load translator
        
        // Route translator
        $app->getEventManager()->attach('route', array(
            $this,
            'onPreRoute'
        ), 4);
    }

    public function onPreRoute($e)
    {
        $app = $e->getTarget();
        $serviceManager = $app->getServiceManager();
        $serviceManager->get('router')->setTranslator($serviceManager->get('translator'));
        
        $lm = $serviceManager->get('Language\Manager\LanguageManager');
        $code = $lm->getLanguageFromRequest()->getCode();
        
        $translator = $serviceManager->get('translator');
        $translator->addTranslationFile('PhpArray', __DIR__ . '/language/routes/'.$code.'.php', 'default', $code);
        $translator->setLocale($code);
        
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }
}